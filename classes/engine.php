<?php

namespace autodeploy;

class engine
{

    const STDIN    = 0;
    const STDOUT   = 1;
    const STDERR   = 2;

    protected $command = null;

    private $processes = array();


    /**
     * @param $command
     */
    public function __construct($command)
    {
        $this->setCommand($command);
    }

    /**
     * @param $command
     * @return engine
     */
    public function setCommand($command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * @param \Closure $oClosureStdout
     * @param \Closure $oClosureStderr
     * @return engine
     */
    public function execute(\Closure $oClosureStdout, \Closure $oClosureStderr)
    {
        $this->run()->read(array(
            self::STDOUT => $oClosureStdout,
            self::STDERR => $oClosureStderr,
        ));

        return $this;
    }

    /**
     * @return engine
     */
    protected function run()
    {
        $resource = proc_open(
            $this->command,
            array(
                self::STDIN  => array("pipe", "r"),
                self::STDOUT => array("pipe", "w"),
                self::STDERR => array("pipe", "w"),
            ),
            $pipes,
            null,
            $_ENV
        );

        stream_set_blocking($pipes[ self::STDOUT ], 1);
        stream_set_blocking($pipes[ self::STDERR ], 1);

        fclose($pipes[ self::STDIN ]);
        unset($pipes[ self::STDIN ]);

        $this->processes[] = (object) array(
            'resource' => $resource,
            'pipes'    => $pipes,
        );

        return $this;
    }

    /**
     * @param array $closures
     * @return engine
     */
    protected function read(array $closures)
    {
        $null = null;

        while (count($this->processes))
        {
            $pipes = array();

            foreach ($this->processes as $process)
            {
                foreach (array_keys($closures) as $stream)
                {
                    if (array_key_exists($stream, $process->pipes))
                    {
                        $pipes[] = $process->pipes[ $stream ];
                    }
                }
            }

            if (stream_select($pipes, $null, $null, 0))
            {
                foreach ($this->processes as $i => $process)
                {
                    foreach ($closures as $stream => $fn)
                    {
                        if (array_key_exists($stream, $process->pipes) && in_array($process->pipes[ $stream ], $pipes) === true)
                        {
                            //call_user_func($fn, stream_get_contents($process->pipes[ $stream ]));
                            call_user_func($fn, stream_get_line($process->pipes[ $stream ], 1024));

                            if (feof($process->pipes[ $stream ]) === true)
                            {
                                fclose($process->pipes[ $stream ]);
                                unset($process->pipes[ $stream ]);
                            }
                        }
                    }

                    if (!count($process->pipes))
                    {
                        $phpStatus = proc_get_status($process->resource);

                        while ($phpStatus['running'] == true)
                        {
                            $phpStatus = proc_get_status($process->resource);
                        }

                        proc_close($process->resource);

                        unset($this->processes[$i]);
                    }
                }
            }
        }

        return $this;
    }

}
