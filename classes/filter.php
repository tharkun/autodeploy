<?php

namespace autodeploy;

abstract class filter implements aggregators\runner, definitions\php\observable, definitions\filter
{

    protected $runner = null;

    /**
     * @param runner $runner
     */
    final public function __construct(runner $runner)
    {
        $this->setRunner($runner);
    }

    /**
     * @param runner $runner
     * @return filter
     */
    final public function setRunner(runner $runner)
    {
        $this->runner = $runner;

        return $this;
    }

    /**
     * @return runner
     */
    final public function getRunner()
    {
        return $this->runner;
    }

    /**
     * @param definitions\php\observer $observer
     * @return step
     */
    public function addObserver(definitions\php\observer $observer)
    {
        $this->observers[] = $observer;

        return $this;
    }

    /**
     * @param $event
     * @return step
     */
    public function callObservers($event)
    {
        foreach ($this->observers as $observer)
        {
            $observer->handleEvent($event, $this);
        }

        return $this;
    }
}
