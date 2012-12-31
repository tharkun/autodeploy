<?php

namespace autodeploy;

abstract class command implements aggregators\runner
{

    protected $runner = null;
    protected $options = array();

    protected $wildcard = null;

    /**
     * @param runner $runner
     */
    public function __construct(runner $runner)
    {
        $this->setRunner($runner);
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
     * @param array $options
     * @return ezpgenerateautoloads
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param $wildcard
     * @return ezgeneratetranslationcache
     */
    public function setWildcard($wildcard)
    {
        $this->wildcard = $wildcard;

        return $this;
    }

    /**
     * @return array
     */
    public function getWildcard()
    {
        return $this->wildcard;
    }

    /**
     * @throws \RuntimeException
     */
    public function __toString()
    {
        throw new \RuntimeException('__toString method in command class can be called.');
    }

}