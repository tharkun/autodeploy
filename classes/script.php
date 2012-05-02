<?php

namespace autodeploy;

abstract class script implements aggregators\php\adapter, aggregators\php\locale
{

    protected $name = '';
    protected $runner = null;
    protected $locale = null;
    protected $adapter = null;

    private $argumentsParser = null;


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    public function __construct($name, runner $runner = null)
    {
        $this
            ->setName($name)
            ->setRunner($runner ?: new runner())
            ->setLocale(new php\locale())
            ->setAdapter(new php\adapter())
            ->setArgumentsParser(new php\arguments\parser())
        ;

        if ($this->adapter->php_sapi_name() !== 'cli')
        {
            throw new \LogicException('\'' . $this->getName() . '\' must be used in CLI only');
        }

        $this->setArgumentCommonHandlers();
        $this->setArgumentHandlers();
    }

    /**
     * @param $name
     * @return script
     */
    public function setName($name)
    {
        $this->name = (string) $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \autodeploy\runner $runner
     * @return runner
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
     * @param php\locale $locale
     * @return script
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
     * @param php\adapter $adapter
     * @return script
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
     * @param php\arguments\parser $parser
     * @return script
     */
    public function setArgumentsParser(php\arguments\parser $parser)
    {
        $this->argumentsParser = $parser;

        return $this;
    }

    /**
     * @return null
     */
    public function getArgumentsParser()
    {
        return $this->argumentsParser;
    }

    /**
     * @return script
     * @throws \InvalidArgument
     */
    final protected function setArgumentCommonHandlers()
    {
        $runner = $this->getRunner();

        $this->addArgumentHandler(
            function($script, $argument, $values)
            {
                if (sizeof($values) != 0)
                {
                    throw new \InvalidArgument(sprintf($script->getLocale()->_('Bad usage of %s, do php %s --help for more informations'), $argument, $script->getName()));
                }

                outputs\cli::forceTerminal();
            },
            array('-c', '--color'),
            null,
            $this->locale->_('Use color')
        );

        $this->addArgumentHandler(
            function($script, $argument, $values) use ($runner) {
                if (sizeof($values) != 1)
                {
                    throw new \InvalidArgument(sprintf($script->getLocale()->_('Bad usage of %s, do php %s --help for more informations'), $argument, $script->getName()));
                }

                $bootstrapFile = realpath($values[0]);

                if ($bootstrapFile === false || is_file($bootstrapFile) === false || is_readable($bootstrapFile) === false)
                {
                    throw new \InvalidArgument(sprintf($script->getLocale()->_('Bootstrap file \'%s\' does not exist'), $values[0]));
                }

                $runner->setBootstrapFile($bootstrapFile);
            },
            array('-bf', '--bootstrap-file'),
            '<file>',
            $this->locale->_('Include <file> before executing each test method')
        );

        return $this;
    }

    /**
     * @param \Closure $handler
     * @param array $args
     * @param null $values
     * @param null $help
     * @return script
     */
    public function addArgumentHandler(\Closure $handler, array $args, $values = null, $help = null)
    {
        if ($help !== null)
        {
            $this->help[] = array($args, $values, $help);
        }

        $this->argumentsParser->addHandler($handler, $args);

        return $this;
    }

    /**
     * @param array $args
     * @return script
     */
    public function run(array $args = array())
    {
        $this->adapter->ini_set('log_errors_max_len', '0');
        $this->adapter->ini_set('log_errors', 'Off');
        $this->adapter->ini_set('display_errors', 'stderr');

        $this->argumentsParser->parse($this, $args);

        if ($this->getRunner()->hasReports() === false)
        {
            $report = new reports\synchronous\cli();
            $report->addWriter(new writers\std\out());

            $this->getRunner()->addReport($report);
        }

        return $this;
    }

    protected abstract function setArgumentHandlers();

}
