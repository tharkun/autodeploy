<?php

namespace autodeploy;

abstract class command implements aggregators\runner
{

    protected $runner = null;
    protected $options = array();

    protected $wildcards = array();

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
     * @param array $wildcards
     * @return command
     */
    public function setWildcards(array $wildcards)
    {
        $this->wildcards = $wildcards;

        return $this;
    }

    /**
     * @return array
     */
    public function getWildcards()
    {
        return $this->wildcards;
    }

    /**
     * @param $wildcards
     * @return command
     */
    public function addWildcard($wildcards)
    {
        if (!is_array($wildcards))
        {
            $wildcards = array($wildcards);
        }
        foreach ($wildcards as $wildcard)
        {
            if (!in_array($wildcard, $this->wildcards))
            {
                $this->wildcards[] = $wildcard;
            }
        }

        return $this;
    }

    /**
     * @throws \RuntimeException
     */
    public function __toString()
    {
        throw new \RuntimeException('__toString method in command class can be called.');
    }

    /**
     * @param $wildcard
     * @return mixed
     */
    public function cleanPath($wildcard)
    {
        return $this->getRunner()->getSystem()->cleanPath( $wildcard );
    }

}