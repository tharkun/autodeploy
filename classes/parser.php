<?php

namespace autodeploy;

abstract class parser implements aggregators\runner, definitions\php\observable, definitions\parser
{

    protected static $singleton = null;

    protected $runner = null;
    protected $observers = array();
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
     * Get another instance of a autodeploy\parser
     * @final
     * @param string $sParser
     * @return parser
     */
    final protected function getOtherInstance($sParser)
    {
        preg_match("/(svn|rsync)$/", get_class($this), $aMatches);

        return factories\profile\parser::instance(
             $this->getRunner()->getProfiles()->current()->getName(),
             $sParser,
             strtolower($aMatches[1])
        )
            ->with($this->getRunner())
            ->make()
        ;
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
     * @param php\iterator $iterator
     * @return parser
     */
    public function parse(php\iterator $iterator)
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
                    'name'  => $matches[0],
                    'match' => $matches[$matchOffset],
                );
            }
            else
            {
                $this->matches[] = array(
                    'name'  => $matches[0],
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

        if (count($this->matches)) {
            $wildcards = array();
            foreach ($this->matches as $match)
            {
                $wildcards[] = $match['match'];
            }

            //$type = $this->getTaskType();
            foreach (array_unique($wildcards) as $wildcard)
            {
                //$tasks[] = array($type, $wildcard);
                $tasks[] = $wildcard;
            }
        }

        return $tasks;
    }

}
