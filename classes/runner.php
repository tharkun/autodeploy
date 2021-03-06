<?php

namespace autodeploy;

class runner implements aggregators\php\adapter, aggregators\php\locale, definitions\php\observable
{

    const runStart = 'runnerStart';
    const runStop = 'runnerStop';

    protected $adapter = null;
    protected $locale = null;
    protected $system = null;
    protected $debug = null;
    protected $profiles = null;

    protected $script = null;

    protected $bootstrapFiles = null;

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

    protected $commands = array();


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    public function __construct(php\adapter $adapter = null, php\locale $locale = null, php\system $system = null, php\iterator $iterator = null)
    {
        $this
            ->setAdapter($adapter ?: new php\adapter())
            ->setLocale($locale ?: new php\locale())
            ->setSystem($system ?: new php\system())
            ->setProfiles($iterator ?: new php\iterator())
        ;

        $this->setBootstrapFiles(new php\iterator());

        $this->setDebug(php\debug::instance());

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
     * @param php\debug $debug
     * @return runner
     */
    public function setDebug(php\debug $debug)
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * @return null
     */
    public function getDebug()
    {
        return $this->debug;
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
     * @param php\iterator $iterator
     * @return runner
     */
    public function setProfiles(php\iterator $iterator)
    {
        $this->profiles = $iterator;

        return $this;
    }

    /**
     * @return null|profile
     */
    public function getProfiles()
    {
        return $this->profiles;
    }

    /**
     * @param string $name
     * @param bool $reset
     * @return runner
     */
    public function addProfile($name = 'simple', $reset = false)
    {
        if ($reset === true)
        {
            $this->profiles->reset();
        }

        $profile = sprintf('%s\profiles\%s', __NAMESPACE__, $name);
        $this->profiles->append(new $profile());

        return $this;
    }

    /**
     * @param script $script
     * @return runner
     */
    public function setScript(script $script)
    {
        $this->script = $script;

        return $this;
    }

    /**
     * @return null|profile
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * @param $iterator
     * @return runner
     */
    public function setBootstrapFiles(php\iterator $iterator)
    {
        $this->bootstrapFiles = $iterator;

        return $this;
    }

    /**
     * @return null
     */
    public function getBootstrapFiles()
    {
        return $this->bootstrapFiles;
    }

    /**
     * @param $path
     * @return runner
     */
    public function addBootstrapFile($path)
    {
        $this->getBootstrapFiles()->append($path);

        return $this;
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
     * @param array $commands
     * @param bool $add
     * @return runner
     */
    public function setCommands(array $commands, $add = false)
    {
        if ($add === true)
        {
            foreach ($commands as $command) $this->commands[] = $command;
        }
        else
        {
            $this->commands = $commands;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
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
     * @param $message
     * @param bool $eol
     * @return runner
     */
    public function writeMessage($message, $eol = true)
    {
        $message = rtrim($message);

        if ($eol == true)
        {
            $message .= PHP_EOL;
        }

        $this->outWriter->write($message);

        return $this;
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
    public function includeBootstrapFiles()
    {
        $runner = $this;

        $include = function($path) use ($runner) { include_once($path); };

        if (($files = $this->getBootstrapFiles()) !== null)
        {
            foreach ($files as $path)
            {
                try
                {
                    $include( $path );
                }
                catch (\Exception $exception)
                {
                    throw new \RuntimeException(sprintf($this->getLocale()->_('Unable to include \'%s\''), $path));
                }
            }
        }

        return $this;
    }

    /**
     * @return runner
     */
    public function run()
    {
        $this->startTime = microtime(true);

        $this->includeBootstrapFiles();

        $this->callObservers(self::runStart);

        $this->steps->rewind();
        while ($this->steps->valid())
        {
            $step = $this->steps->current();

            $factory = factories\step::instance($step['type']);

            $object = $factory->with($this, $step['factories'])->make();

            if ($factory->getReflectionClass()->implementsInterface(__NAMESPACE__ . "\\definitions\\php\\observable"))
            {
                $this->stepNumber++;

                foreach ($this->observers as $observer)
                {
                    $object->addObserver($observer);
                }
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
