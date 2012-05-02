<?php

namespace autodeploy;

class runner implements aggregators\php\adapter, aggregators\php\locale, definitions\php\observable
{

    const runStart = 'runnerStart';
    const runStop = 'runnerStop';

    protected $adapter = null;
    protected $locale = null;
    protected $system = null;
    protected $profil = null;

    protected $bootstrapFile = null;
    protected $defaultReportTitle = null;

    protected $reports = array();
    protected $observers = array();

    protected $steps = array();
    protected $stepNumber = 0;





    protected $filesIterator = null;
    protected $elementsIterator = null;
    protected $tasksIterator = null;

    private $start = null;
    private $stop = null;


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    public function __construct(php\adapter $adapter = null, php\locale $locale = null, system $system = null, profil $profil = null)
    {
        $this
            ->setAdapter($adapter ?: new php\adapter())
            ->setLocale($locale ?: new php\locale())
            ->setSystem($system ?: new system())
            ->setProfil($profil ?: new profil())
        ;

        $this
            ->setFilesIterator( new iterator() )
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
     * @param system $system
     * @return runner
     */
    public function setSystem(system $system)
    {
        $this->system = $system;

        return $this;
    }

    /**
     * @return null|system
     */
    public function getSystem()
    {
        return $this->system;
    }

    /**
     * @param profil $profil
     * @return runner
     */
    public function setProfil(profil $profil)
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * @return null|profil
     */
    public function getProfil()
    {
        return $this->profil;
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
        /*foreach ($steps as $name => $step)
        {

        }*/
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
     * @param iterator $filesIterator
     * @return runner
     */
    public function setFilesIterator(iterator $filesIterator)
    {
        $this->filesIterator = $filesIterator;

        return $this;
    }

    /**
     * @return null
     */
    public function getFilesIterator()
    {
        return $this->filesIterator;
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
