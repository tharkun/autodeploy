<?php

namespace autodeploy;

abstract class step implements aggregators\runner, definitions\php\observable, definitions\step
{

    const runStart = 'stepStart';
    const runStop = 'stepStop';

    protected $runner = null;
    protected $observers = array();

    private $start = null;
    private $stop = null;

    /**
     * @param runner $runner
     */
    public function __construct(runner $runner)
    {
        $this->setRunner($runner);
    }

    /**
     * @param runner $runner
     * @return step
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

    public function getDuration()
    {
        return ($this->start === null || $this->stop === null ? null : $this->stop - $this->start);
    }

    /**
     * @return step
     */
    public function run()
    {
        $this->start = $this->getRunner()->getAdapter()->microtime(true);

        $this->callObservers(self::runStart);
        $this->callObservers(static::runStart);

        $this->runStep();

        $this->stop = $this->getRunner()->getAdapter()->microtime(true);

        $this->callObservers(static::runStop);
        $this->callObservers(self::runStop);

        return $this;
    }

}
