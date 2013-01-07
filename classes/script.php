<?php

namespace autodeploy;

use autodeploy\php\arguments\parser;

abstract class script implements aggregators\runner, aggregators\php\adapter, aggregators\php\locale
{

    protected $name = '';
    protected $runner = null;
    protected $locale = null;
    protected $adapter = null;

    private $argumentsParser = null;
    protected $runScript = true;
    protected $help = array();


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
            ->setArgumentsParser(new parser())
        ;

        $this->getRunner()->setScript($this);

        if ($this->adapter->php_sapi_name() !== 'cli')
        {
            throw new \LogicException('\'' . $this->getName() . '\' must be used in CLI only');
        }
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
    public function setArgumentsParser(parser $parser)
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
     * @throws \InvalidArgumentException
     */
    final protected function setArgumentCommonHandlers()
    {
        $runner = $this->getRunner();

        $this->addArgumentHandler(
            function($script, $argument, $values)
            {
                $script->help();
            },
            array('-h', '--help'),
            parser::TYPE_NONE,
            parser::OPTIONNAL,
            null,
            'Display help'
        );

        $this->addArgumentHandler(
            function($script, $argument, $values)
            {
                php\sapi\cli::forceTerminal();
            },
            array('-c', '--color'),
            parser::TYPE_NONE,
            parser::OPTIONNAL,
            null,
            'Use color'
        );

        $this->addArgumentHandler(
            function($script, $argument, $values)
            {
                commands\php::setPhpPath(current($values));
            },
            array('-p', '--php'),
            parser::TYPE_SINGLE,
            parser::OPTIONNAL,
            null,
            'Path to php binary'
        );

        $this->addArgumentHandler(
            function($script, $argument, $values) use ($runner)
            {
                $runner->setQuiet(true);
            },
            array('-q', '--quiet'),
            parser::TYPE_NONE,
            parser::OPTIONNAL,
            '<path/to/php/binary>',
            'Is quiet'
        );

        $this->addArgumentHandler(
            function($script, $argument, $values) use ($runner)
            {
                $runner->setPromptBetweenSteps(true);
            },
            array('-pbs', '--prompt-between-steps'),
            parser::TYPE_NONE,
            parser::OPTIONNAL,
            null,
            'Prompt between steps'
        );

        $this->addArgumentHandler(
            function($script, $argument, $values) use ($runner)
            {
                $runner->setPromptBeforeExecution(true);
            },
            array('-pbe', '--prompt-before-execution'),
            parser::TYPE_NONE,
            parser::OPTIONNAL,
            null,
            'Prompt before command execution'
        );

        $this->addArgumentHandler(
            function($script, $argument, $values) use ($runner)
            {
                foreach ($values as $value)
                {
                    $bootstrapFile = realpath($value);

                    if ($bootstrapFile === false || is_file($bootstrapFile) === false || is_readable($bootstrapFile) === false)
                    {
                        throw new \InvalidArgumentException(sprintf($script->getLocale()->_('Bootstrap file \'%s\' does not exist'), $value));
                    }

                    $runner->addBootstrapFile($bootstrapFile);
                }
            },
            array('-bf', '--bootstrap-file'),
            parser::TYPE_MULTIPLE,
            parser::OPTIONNAL,
            'file',
            'Include %s before executing each test method'
        );

        $this->addArgumentHandler(
            function($script, $argument, $values) use ($runner)
            {
                $mailer = new php\mailers\mail();
                $mailer->setSubject(__FILE__);
                foreach ($values as $value)
                {
                    $mailer->addTo($value);
                }

                $mailWriter = new writers\mail();
                $mailWriter->setMailer($mailer);

                $builderReport = new reports\asynchronous\mail();
                $builderReport->addWriter($mailWriter);

                $runner->addReport($builderReport);
            },
            array('-t', '--to'),
            parser::TYPE_MULTIPLE,
            parser::OPTIONNAL,
            'email',
            'Send report to %s'
        );

        return $this;
    }

    /**
     * @param \Closure $handler
     * @param array $args
     * @param int $type
     * @param int $mandatory
     * @param null $values
     * @param null $help
     * @return script
     */
    final public function addArgumentHandler(\Closure $handler, array $args, $type = parser::TYPE_ALL, $mandatory = parser::OPTIONNAL, $values = null, $help = null)
    {
        if ($help !== null)
        {
            $this->help[] = array(
                $args,
                $type,
                $mandatory,
                $values,
                $this->locale->_($help)
            );
        }

        $this->argumentsParser->addHandler($handler, $args, $type, $mandatory);

        return $this;
    }

    /**
     * @param array $args
     * @return script
     */
    public function init(array $args = array())
    {
        if ($this->getRunner()->hasReports() === false)
        {
            $report = new reports\synchronous\cli();
            $report->addWriter(new writers\std\out());

            $this->getRunner()->addReport($report);
        }

        $this->setArgumentCommonHandlers();
        $this->setArgumentHandlers();

        $this->setStepHandlers();

        $this->adapter->ini_set('log_errors_max_len', '0');
        $this->adapter->ini_set('log_errors', 'Off');
        $this->adapter->ini_set('display_errors', 'stderr');

        return $this;
    }

    /**
     * @throws \Exception
     * @param array $args
     * @return runner
     */
    final public function run(array $args = array())
    {
        try
        {
            $this->init($args);

            if ($this->runScript)
            {
                $this->argumentsParser->parse($this, $args);

                $this->getRunner()->run();
            }
        }
        catch (\Exception $exception)
        {
            throw $exception;
        }

        return $this;
    }

    public function help()
    {
        $this->runScript = false;

        if ($this->help)
        {
            $this->getRunner()->writeMessage(sprintf($this->locale->_('Usage: %s [options]'), $this->getName()) . PHP_EOL);
            $this->getRunner()->writeMessage($this->locale->_('Options:') . PHP_EOL);

            $rows = array();
            foreach ($this->help as $help)
            {
                $option = '';
                if (php\arguments\parser::TYPE_SINGLE === $help[1])
                {
                    $option .= sprintf(' <%s>', $help[3]);
                }
                else if (php\arguments\parser::TYPE_MULTIPLE === $help[1])
                {
                    $option .= sprintf(' <%s...>', $help[3]);
                }

                $rows[] = array(
                    isset($help[0][0]) ? trim($help[0][0] . $option) : '',
                    isset($help[0][1]) ? trim($help[0][1] . $option) : '',
                    $help[2] ? 'no' : 'yes',
                    sprintf($help[4], trim($option)),
                );
            }

            $this->getRunner()->writeMessage((string) new php\sapi\cli\table( $rows, array('Short', 'Long', 'Optionnal', 'Description') ) . PHP_EOL);
        }

        return $this;
    }

    protected abstract function setArgumentHandlers();
    protected abstract function setStepHandlers();

}
