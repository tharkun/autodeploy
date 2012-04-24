<?php

namespace autodeploy;

abstract class script implements aggregators\php\adapter, aggregators\php\locale
{

    protected $name = '';
    protected $locale = null;
    protected $adapter = null;

    private $argumentsParser = null;

    public function __construct($name)
    {
        $this
            ->setName($name)
            ->setLocale(new php\locale())
            ->setAdapter(new php\adapter())
            ->setArgumentsParser(new php\arguments\parser())
        ;

        if ($this->adapter->php_sapi_name() !== 'cli')
        {
            throw new \LogicException('\'' . $this->getName() . '\' must be used in CLI only');
         }
    }

    public function setName($name)
    {
        $this->name = (string) $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setLocale(php\locale $locale)
    {
        $this->locale = $locale;

        return $this;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setAdapter(php\adapter $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function setArgumentsParser(php\arguments\parser $parser)
    {
        $this->argumentsParser = $parser;

        $this->setArgumentHandlers();

        return $this;
    }

    public function getArgumentsParser()
    {
        return $this->argumentsParser;
    }

    public function addArgumentHandler(\Closure $handler, array $args, $values = null, $help = null)
    {
        if ($help !== null)
        {
            $this->help[] = array($args, $values, $help);
        }

        $this->argumentsParser->addHandler($handler, $args);

        return $this;
    }

    public function run(array $args = array())
    {
        $this->adapter->ini_set('log_errors_max_len', '0');
        $this->adapter->ini_set('log_errors', 'Off');
        $this->adapter->ini_set('display_errors', 'stderr');

        $this->argumentsParser->parse($this, $args);

        return $this;
    }

    protected abstract function setArgumentHandlers();

}
