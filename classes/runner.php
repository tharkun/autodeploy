<?php

namespace autodeploy;

class runner implements aggregators\php\adapter, aggregators\php\locale, definitions\php\observable
{

    const runStart = 'runnerStart';
    const runStop = 'runnerStop';

    protected $adapter = null;
    protected $locale = null;
    protected $system = null;
    protected $profile = null;

    protected $bootstrapFile = null;
    protected $defaultReportTitle = null;

    protected $reports = array();
    protected $observers = array();

    protected $steps = array();
    protected $stepNumber = 0;





    protected $inputIterator = null;
    protected $elementsIterator = null;
    protected $tasksIterator = null;

    private $start = null;
    private $stop = null;


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    public function __construct(php\adapter $adapter = null, php\locale $locale = null, php\system $system = null, profile $profile = null)
    {
        $this
            ->setAdapter($adapter ?: new php\adapter())
            ->setLocale($locale ?: new php\locale())
            ->setSystem($system ?: new php\system())
            ->setProfile($profile ?: new profile())
        ;

        $this
            ->setInputIterator( new iterator() )
            ->setElementsIterator( new iterator() )
            ->setTasksIterator( new iterator() )
        ;
    }

    /**
     * @param php\adapter $adapter
     * @return runner
     */
    public function setAdapter(php\adapter $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * @return null
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param php\locale $locale
     * @return runner
     */
    public function setLocale(php\locale $locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return null
     */
    public function getLocale()
    {
        return $this->locale;
    }


    public function setBootstrapFile($path)
    {
        $this->bootstrapFile = $path;

        return $this;
    }

    public function getBootstrapFile()
    {
        return $this->bootstrapFile;
    }

    public function addReport(report $report)
    {
        $this->reports[] = $report;

        return $this->addObserver($report);
    }

    public function hasReports()
    {
        return (sizeof($this->reports) > 0);
    }

    public function addObserver(definitions\php\observer $observer)
    {
        $this->observers[] = $observer;

        return $this;
    }

    public function callObservers($event)
    {
        foreach ($this->observers as $observer)
        {
            $observer->handleEvent($event, $this);
        }

        return $this;
    }

    public function run()
    {
        $this->start = microtime(true);

        if ($this->defaultReportTitle !== null)
        {
            foreach ($this->reports as $report)
            {
                if ($report->getTitle() === null)
                {
                    $report->setTitle($this->defaultReportTitle);
                }
            }
        }

        $this->includeBootstrapFile();

        $this->callObservers(self::runStart);

        foreach ($this->getSteps() as $step => $a)
        {
            $this->stepNumber++;

            $object = factories\step::build($step, $this, $a);

            foreach ($this->observers as $observer)
            {
                $object->addObserver($observer);
            }

            $object->run();
        }

        $this->stop = microtime(true);

        $this->callObservers(self::runStop);

        return $this;
    }

    public function getDuration()
    {
        return ($this->start === null || $this->stop === null ? null : $this->stop - $this->start);
    }


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    /**
     * @param php\system $system
     * @return runner
     */
    public function setSystem(php\system $system)
    {
        $this->system = $system;

        return $this;
    }

    /**
     * @return null|php\system
     */
    public function getSystem()
    {
        return $this->system;
    }

    /**
     * @param profile $profile
     * @return runner
     */
    public function setProfile(profile $profile)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * @return null|profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @throws \RuntimeException
     * @return runner
     */
    public function includeBootstrapFile()
    {
        $runner = $this;

        $include = function($path) use ($runner) { include_once($path); };

        if (($path = $this->getBootstrapFile()) !== null)
        {
            try
            {
                $include( $path = $this->getBootstrapFile() );
            }
            catch (\Exception $exception)
            {
                throw new \RuntimeException(sprintf($this->getLocale()->_('Unable to include \'%s\''), $path));
            }
        }

        return $this;
    }


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    public function setSteps(array $steps)
    {
        foreach ($steps as $name => $step)
        {
            if (!in_array($name, step::$availableSteps))
            {
                throw new \InvalidArgumentException(sprintf($this->getLocale()->_('Step \'%s\' does not exist'), $name));
            }

            if (!is_array($step) && !($step instanceof \Closure))
            {
                throw new \InvalidArgumentException(sprintf($this->getLocale()->_('Step \'%s\' should be composed of an array of closure or a single closure'), $name));
            }

            if (!is_array($step) && $step instanceof \Closure)
            {
                $steps[$name] = array($step);
            }

            foreach ($step as $closure)
            {
                if (!($closure instanceof \Closure))
                {
                    throw new \InvalidArgumentException(sprintf($this->getLocale()->_('Step \'%s\' should be composed of an array of closure'), $name));
                }
            }
        }

        $this->steps = $steps;

        return $this;
    }

    public function getSteps()
    {
        return $this->steps;
    }

    public function getStepNumber()
    {
        return $this->stepNumber;
    }



    /**
     * @param iterator $inputIterator
     * @return runner
     */
    public function setInputIterator(iterator $inputIterator)
    {
        $this->inputIterator = $inputIterator;

        return $this;
    }

    /**
     * @return null
     */
    public function getInputIterator()
    {
        return $this->inputIterator;
    }

    /**
     * @param iterator $elementsIterator
     * @return runner
     */
    public function setElementsIterator(iterator $elementsIterator)
    {
        $this->elementsIterator = $elementsIterator;

        return $this;
    }

    /**
     * @return null
     */
    public function getElementsIterator()
    {
        return $this->elementsIterator;
    }

    /**
     * @param iterator $tasksIterator
     * @return runner
     */
    public function setTasksIterator(iterator $tasksIterator)
    {
        $this->tasksIterator = $tasksIterator;

        return $this;
    }

    /**
     * @return null
     */
    public function getTasksIterator()
    {
        return $this->tasksIterator;
    }

}
