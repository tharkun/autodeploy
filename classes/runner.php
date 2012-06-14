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

    protected $reports = array();
    protected $observers = array();

    private $steps = null;
    protected $stepNumber = 0;

    protected $iterator = null;

    private $startTime = 0;
    private $stopTime  = 0;

    protected $outWriter = null;
    protected $errWriter = null;

    protected $quiet = false;
    protected $promptBetweenSteps = false;
    protected $promptBeforeExecution = false;


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

        $this->steps    = new php\iterator();
        $this->iterator = new php\iterator\recursive( array(new php\iterator()) );

        $this->setOutWriter(new writers\std\out());
        $this->setErrWriter(new writers\std\err());
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
     * @param $path
     * @return runner
     */
    public function setBootstrapFile($path)
    {
        $this->bootstrapFile = $path;

        return $this;
    }

    /**
     * @return null
     */
    public function getBootstrapFile()
    {
        return $this->bootstrapFile;
    }

    /**
     * @param report $report
     * @return runner
     */
    public function addReport(report $report)
    {
        $this->reports[] = $report;

        return $this->addObserver($report);
    }

    /**
     * @return bool
     */
    public function hasReports()
    {
        return (sizeof($this->reports) > 0);
    }

    /**
     * @param definitions\php\observer $observer
     * @return runner
     */
    public function addObserver(definitions\php\observer $observer)
    {
        $this->observers[] = $observer;

        return $this;
    }

    /**
     * @param $event
     * @return runner
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
     * @param array $steps
     * @return runner
     */
    public function setSteps(array $steps)
    {
        foreach ($steps as $step)
        {
            $this->addStep($step['type'], $step['factories']);
        }

        return $this;
    }

    /**
     * @return php\iterator|null
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * @param $type
     * @param $factories
     * @return runner
     * @throws \InvalidArgumentException
     */
    final public function addStep($type, $factories)
    {
        if (!in_array($type, step::$availableSteps))
        {
            throw new \InvalidArgumentException(sprintf($this->getLocale()->_('Step \'%s\' does not exist'), $type));
        }

        if (!is_array($factories) && !($factories instanceof \Closure))
        {
            throw new \InvalidArgumentException(sprintf($this->getLocale()->_('Step \'%s\' should be composed of an array of closure or a single closure'), $type));
        }

        if (!is_array($factories) && $factories instanceof \Closure)
        {
            $factories = array( $factories );
        }

        foreach ($factories as $closure)
        {
            if (!($closure instanceof \Closure))
            {
                throw new \InvalidArgumentException(sprintf($this->getLocale()->_('Step \'%s\' should be composed of an array of closure'), $type));
            }
        }

        $this->steps->append( array(
            'type'      => $type,
            'factories' => $factories
        ));

        return $this;
    }

    /**
     * @param $stepNumber
     * @return runner
     */
    public function setStepNumber($stepNumber)
    {
        $this->stepNumber = $stepNumber;

        return $this;
    }

    /**
     * @return int
     */
    public function getStepNumber()
    {
        return $this->stepNumber;
    }

    /**
     * @return php\iterator\recursive
     */
    public function getIterator()
    {
        return $this->iterator;
    }

    /**
     * @param writer $writer
     * @return runner
     */
    public function setOutWriter(writer $writer)
    {
        $this->outWriter = $writer;

        return $this;
    }

    /**
     * @return null
     */
    public function getOutWriter()
    {
        return $this->outWriter;
    }

    /**
     * @param writer $writer
     * @return runner
     */
    public function setErrWriter(writer $writer)
    {
        $this->errWriter = $writer;

        return $this;
    }

    /**
     * @return null
     */
    public function getErrWriter()
    {
        return $this->errWriter;
    }

    /**
     * @param $bool
     * @return runner
     */
    public function setQuiet($bool)
    {
        $this->quiet = (bool) $bool;

        return $this;
    }

    /**
     * @return bool
     */
    public function getQuiet()
    {
        return $this->quiet;
    }

    /**
     * @param $bool
     * @return runner
     */
    public function setPromptBetweenSteps($bool)
    {
        $this->promptBetweenSteps = (bool) $bool;

        return $this;
    }

    /**
     * @return bool
     */
    public function getPromptBetweenSteps()
    {
        return $this->promptBetweenSteps;
    }

    /**
     * @param $bool
     * @return runner
     */
    public function setPromptBeforeExecution($bool)
    {
        $this->promptBeforeExecution = $bool;

        return $this;
    }

    /**
     * @return bool
     */
    public function getPromptBeforeExecution()
    {
        return $this->promptBeforeExecution;
    }

    /**
     * @param $message
     * @return string
     */
    public function prompt($message)
    {
        $this->outWriter->write($message);

        return trim($this->adapter->fgets(STDIN));
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->stopTime - $this->startTime;
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



    public function run()
    {
        $this->startTime = microtime(true);

        $this->includeBootstrapFile();

        $this->callObservers(self::runStart);

        $this->steps->rewind();
        while ($this->steps->valid())
        {
            $step = $this->steps->current();

            $this->stepNumber++;

            $object = factories\step::build($step['type'], $this, $step['factories']);

            foreach ($this->observers as $observer)
            {
                $object->addObserver($observer);
            }

            $object->run();

            $this->steps->next();

            if ($this->steps->valid() && $this->promptBetweenSteps === true)
            {
                $this->prompt("Press any key to run next step");
            }
        }

        $this->stopTime = microtime(true);

        $this->callObservers(self::runStop);

        return $this;
    }

}
