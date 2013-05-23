<?php

namespace autodeploy;

class engine
{

    const STDIN    = 0;
    const STDOUT   = 1;
    const STDERR   = 2;

    protected $command = null;

    private $process = null;


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
     * @param callable $oClosureStdout
     * @param callable $oClosureStderr
     * @param callable $oClosureStdin
     * @return $this
     */
    public function execute(\Closure $oClosureStdout, \Closure $oClosureStderr, \Closure $oClosureStdin = null)
    {
        $this->run($oClosureStdin)->read(array(
            self::STDOUT => $oClosureStdout,
            self::STDERR => $oClosureStderr,
        ));

        return $this;
    }

    /**
     * @param callable $oClosureStdin
     * @return $this
     */
    protected function run(\Closure $oClosureStdin = null)
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

        if (!is_null($oClosureStdin))
        {
            call_user_func($oClosureStdin);
        }

        $this->closeStream($pipes, self::STDIN);

        $this->process = (object) array(
            'resource' => $resource,
            'pipes'    => $pipes,
        );

        return $this;
    }

    /**
     * @param array $pipes
     * @param $streamName
     * @return $this
     */
    protected function closeStream(array &$pipes, $streamName)
    {
        fclose($pipes[ $streamName ]);
        unset($pipes[ $streamName ]);

        return $this;
    }

    /**
     * @param array $closures
     * @return engine
     */
    protected function read(array $closures)
    {
        $null = null;

        while (!is_null($this->process))
        {
            $pipes = $this->getPipesFromClosures($closures);

            if (stream_select($pipes, $null, $null, 0))
            {
                foreach ($closures as $stream => $fn)
                {
                    if (array_key_exists($stream, $this->process->pipes) && in_array($this->process->pipes[ $stream ], $pipes) === true)
                    {
                        call_user_func($fn, stream_get_line($this->process->pipes[ $stream ], 1024));

                        if (feof($this->process->pipes[ $stream ]) === true)
                        {
                            $this->closeStream($this->process->pipes, $stream);
                        }
                    }
                }

                if (!count($this->process->pipes))
                {
                    $phpStatus = proc_get_status($this->process->resource);

                    while ($phpStatus['running'] == true)
                    {
                        $phpStatus = proc_get_status($this->process->resource);
                    }

                    proc_close($this->process->resource);

                    $this->process = null;
                }
            }
        }

        return $this;
    }

    /**
     * @param array $closures
     * @return array
     */
    protected function getPipesFromClosures(array $closures)
    {
        $pipes = array();

        foreach (array_keys($closures) as $stream)
        {
            if (array_key_exists($stream, $this->process->pipes))
            {
                $pipes[] = $this->process->pipes[ $stream ];
            }
        }

        return $pipes;
    }

}
