<?php

namespace autodeploy;

abstract class generator implements aggregators\runner, definitions\generator
{

    protected $runner = null;
    protected $wildcard = null;

    /**
     * @param runner $runner
     * @param $wildcard
     */
    final public function __construct(runner $runner, $wildcard)
    {
        $this->setRunner($runner);

        $this->wildcard = $wildcard;
    }

    /**
     * @return void
     */
    final protected function __clone() {}

    /**
     * @param runner $runner
     * @return generator
     */
    final public function setRunner(runner $runner)
    {
        $this->runner = $runner;

        return $this;
    }

    /**
     * @return null
     */
    final public function getRunner()
    {
        return $this->runner;
    }

}
