<?php

namespace autodeploy;

abstract class task implements aggregators\runner, definitions\task
{

    protected $runner = null;

    protected $command = null;
    protected $wildcards = array();


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/

    /**
     * @param runner $runner
     * @param $command
     * @param array $wildcards
     */
    public function __construct(runner $runner, $command, array $wildcards)
    {
        $this
            ->setRunner($runner)
            ->setCommand($command)
            ->setWildcards($wildcards)
        ;
    }

    /**
     * @param runner $runner
     * @return task
     */
    public function setRunner(runner $runner)
    {
        $this->runner = $runner;

        return $this;
    }

    /**
     * @return runner
     */
    public function getRunner()
    {
        return $this->runner;
    }

    /**
     * @param string $command
     * @return task
     */
    public function setCommand($command = null)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * @param array $wildcards
     * @return task
     */
    public function setWildcards(array $wildcards)
    {
        $this->wildcards = $wildcards;

        return $this;
    }


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    /**
     * @return closure
     */
    final public function getClosureForStdout()
    {
        return function ($sOutput)
        {
            echo $sOutput;
        };
    }

    /**
     * @return closure
     */
    final public function getClosureForStderr()
    {
        return function ($sOutput)
        {
            if ('' !== trim($sOutput))
            {
                echo "ERR: ", $sOutput;
            }
        };
    }


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    /**
     * @return task
     */
    public function execute()
    {
        $array = array(
            "Current client" => $sClient = client::FILE_SYSTEM,
            "Command" => $command = (string) $this,
        );

        foreach ($array as $key => $value)
        {
            echo sprintf("%s'%s'", str_pad($key, 30, ' ', STR_PAD_RIGHT), $value) . PHP_EOL;
        }

        factories\client::build($sClient, $this->getRunner(), $command)->execute($this);

        return $this;
    }

}
