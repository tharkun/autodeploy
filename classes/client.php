<?php

namespace autodeploy;

abstract class client implements aggregators\runner
{

    const FILE_SYSTEM      = 'filesystem';
    const GEARMAN          = 'gearman';

    protected $runner = null;
    protected $command = null;

    /**
     * @param runner $runner
     * @param $command
     */
    final public function __construct(runner $runner, $command)
    {
        $this
            ->setRunner($runner)
            ->setCommand($command)
        ;
    }

    /**
     * @param runner $runner
     * @return client
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
     * @param $command
     * @return client
     */
    public function setCommand($command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * @return null
     */
    public function getCommand()
    {
        return $this->command;
    }

}
