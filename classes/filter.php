<?php

namespace autodeploy;

class filter implements aggregators\runner, definitions\filter
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
     * @param iterator $iterator
     * @return filter
     */
    public function filter(iterator $iterator)
    {
        return $this;
    }

}
