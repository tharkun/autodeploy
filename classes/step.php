<?php

namespace autodeploy;

abstract class step implements aggregators\runner, definitions\step
{

    const defaultFactory = 'basic';

    const runStart = 'stepStart';
    const runStop = 'stepStop';

    const STEP_INVOKE       = 'invoke';

    const STEP_TRANSFORM    = 'transform';
    const STEP_FILTER       = 'filter';
    const STEP_PARSE        = 'parse';
    const STEP_GENERATE     = 'generate';
    const STEP_EXECUTE      = 'execute';

    public static $availableSteps = array(
        self::STEP_INVOKE,
        self::STEP_TRANSFORM,
        self::STEP_FILTER,
        self::STEP_PARSE,
        self::STEP_GENERATE,
        self::STEP_EXECUTE,
    );

    protected $runner = null;
    protected $factories = array();
    protected $observers = array();

    private $startTime = 0;
    private $stopTime  = 0;

    private $startMemory = 0;
    private $stopMemory  = 0;

    /**
     * @param runner $runner
     * @param array $factories
     */
    public function __construct(runner $runner, array $factories = array())
    {
        $this->setRunner($runner);
        $this->setFactories($factories);
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
     * @param array $factories
     * @return step
     */
    public function setFactories(array $factories)
    {
        $this->factories = new \ArrayIterator($factories);

        return $this;
    }

    /**
     * @return array
     */
    public function getFactories()
    {
        return $this->factories;
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

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->stopTime - $this->startTime;
    }

    /**
     * @return int
     */
    public function getMemory()
    {
        return $this->stopMemory - $this->startMemory;
    }

    /**
     * @return step
     */
    public function run()
    {
        $this->startTime    = microtime(true);
        $this->startMemory  = memory_get_usage(true);

        $this->callObservers(self::runStart);
        $this->callObservers(static::runStart);

        $this->getFactories()->rewind();

        if ($this->getFactories()->valid())
        {
            $this->getRunner()->getIterator()->end();

            if ($this->getRunner()->getIterator()->getChildren()->count())
            {
                $this->runStep();
            }
        }

        $this->stopMemory   = memory_get_usage(true);
        $this->stopTime     = microtime(true);

        $this->callObservers(static::runStop);
        $this->callObservers(self::runStop);

        return $this;
    }

}
