<?php

namespace autodeploy;

abstract class transformer implements aggregators\runner, definitions\php\observable, definitions\transformer
{

    const runStart = 'transformerStart';
    const runStop = 'transformerStop';

    protected $runner = null;
    protected $observers = array();
    private $collection = array();

    /**
     * @param runner $runner
     */
    final public function __construct(runner $runner)
    {
        $this->setRunner($runner);

        $this->collection = array();
    }

    /**
     * @param runner $runner
     * @return transformer
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

    /**
     * @return php\iterator
     */
    final public function getIterator()
    {
        usort($this->collection, array($this, "compareByFile"));

        $iterator = new php\iterator();

        foreach ($this->collection as $element)
        {
            $iterator->append($element);
        }

        return $iterator;
    }

    /**
     * @param array $aArray
     * @return transformer
     */
    final protected function append(array $aArray)
    {
        $this->collection[] = new element((object) $aArray);

        return $this;
    }

    /**
     * @static
     * @param element $a
     * @param element $b
     * @return int
     */
    final public static function compareByFile(element $a, element $b)
    {
        if ($a->name == $b->name)
        {
            return 0;
        }
        return ($a->name < $b->name) ? -1 : 1;
    }

    /**
     * @param php\iterator $iterator
     * @return transformer
     */
    public function run(php\iterator $iterator)
    {
        foreach ($iterator as $line)
        {
            $this->callObservers(self::runStart);

            $this->transform($line);

            $this->callObservers(self::runStop);
        }

        return $this;
    }
}
