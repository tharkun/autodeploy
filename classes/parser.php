<?php

namespace autodeploy;

abstract class parser implements aggregators\runner, definitions\parser
{

    protected static $singleton = null;

    protected $runner = null;
    protected $matches = array();

    /**
     * @param runner $runner
     */
    protected function __construct(runner $runner)
    {
        $this
            ->setRunner($runner)
            ->setMatches(array())
        ;
    }

    /**
     * @static
     * @param runner $runner
     * @return null
     */
    final public static function instance(runner $runner)
    {
        if (null === static::$singleton)
        {
            static::$singleton = new static($runner);
        }
        static::$singleton->checkSingleton();

        return static::$singleton;
    }

    /**
     * @param runner $runner
     * @return parser
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
     * Get another instance of a autodeploy\parser
     * @final
     * @param string $sParser
     * @return autodeploy\parser
     */
    final protected function getOtherInstance($sParser)
    {
        preg_match("/(svn|rsync)$/", get_class($this), $aMatches);

        return factories\profile\parser::build(
            array(
                 $this->getRunner()->getProfil()->getName(),
                 $sParser,
                 strtolower($aMatches[1])
            ), $this->getRunner()
        );
    }

    /**
     * @throws \LogicException
     * @return parser
     */
    final protected function checkSingleton()
    {
        if (static::$singleton === self::$singleton) {
            throw new \LogicException(sprintf("%s is not supported. Reason: %s.", get_class($this), 'static property $singleton is not set'));
        }

        return $this;
    }

    /**
     * @param array $matches
     * @return parser
     */
    public function setMatches(array $matches)
    {
        $this->matches = $matches;

        return $this;
    }

    /**
     * @param iterator $iterator
     * @return parser
     */
    public function parse(iterator $iterator)
    {
        foreach ($iterator as $element)
        {
            $i = null;
            if ($this->hasMatches($element, $matches, $i) && !is_null($i))
            {
                $this->appendToMatches($matches, $i);
            }
        }

        return $this;
    }

    /**
     * @param array $matches
     * @param $matchOffset
     * @return parser
     */
    final protected function appendToMatches(array $matches, $matchOffset)
    {
        if (count($matches))
        {
            if (isset($matches[$matchOffset]))
            {
                $this->matches[] = array(
                    'file' => $matches[0],
                    'match' => $matches[$matchOffset],
                );
            }
            else
            {
                echo get_class($this), "\n";
                print_r($matches);

                $this->matches[] = array(
                    'file' => $matches[0],
                    'match' => '',
                );
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * @return array
     */
    final public function getTasks()
    {
        $tasks = array();

        if (count($this->getMatches())) {
            $wildcards = array();
            foreach ($this->getMatches() as $match)
            {
                $wildcards[] = $match['match'];
            }

            $type = $this->getTaskType();
            foreach (array_unique($wildcards) as $wildcard)
            {
                $tasks[] = array($type, $wildcard);
            }
        }

        return $tasks;
    }

}
