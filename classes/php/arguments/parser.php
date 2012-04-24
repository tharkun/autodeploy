<?php

namespace autodeploy\php\arguments;

use autodeploy;

class parser implements \iteratorAggregate
{

    protected $superglobals = null;

    protected $values = array();
    private $handlers = array();

    public function __construct(autodeploy\php\superglobals $superglobals = null)
    {
        $this->setSuperglobals($superglobals ?: new autodeploy\php\superglobals());
    }

    public function setSuperglobals(autodeploy\php\superglobals $superglobals)
    {
        $this->superglobals = $superglobals;

        return $this;
    }

    public function getSuperglobals()
    {
        return $this->superglobals;
    }

    public function resetValues()
    {
        $this->values = array();

        return $this;
    }

    public function getValues($argument = null)
    {
        return ($argument === null ? $this->values : (isset($this->values[$argument]) === false ? null : $this->values[$argument]));
    }

    public function getIterator()
    {
        return new \arrayIterator($this->getValues());
    }

    public function parse(autodeploy\script $script, array $array = array())
    {
        if (sizeof($array) <= 0)
        {
            $array = array_slice($this->superglobals->_SERVER['argv'], 1);
        }

        $this->resetValues();

        $arguments = new \arrayIterator($array);

        if (sizeof($arguments) > 0)
        {
            $value = $arguments->current();

            if (self::isArgument($value) === false)
            {
                throw new \UnexpectedValueException('First argument \'' . $value . '\' is invalid');
            }

            $argument = $value;

            $this->values[$argument] = array();

            $arguments->next();

            while ($arguments->valid() === true)
            {
                $value = $arguments->current();

                if (self::isArgument($value) === false)
                {
                    $this->values[$argument][] = $value;
                }
                else
                {
                    $this->trigger($script);

                    $argument = $value;

                    $this->values[$argument] = array();
                }

                $arguments->next();
            }

            $this->trigger($script);
        }

        return $this;
    }

    public function addHandler(\closure $handler, array $arguments)
    {
        $invoke = new \reflectionMethod($handler, '__invoke');

        if ($invoke->getNumberOfParameters() < 3)
        {
            throw new \RuntimeException('Handler must take three arguments');
        }

        foreach ($arguments as $argument)
        {
            if (self::isArgument($argument) === false)
            {
                throw new \RuntimeException('Argument \'' . $argument . '\' is invalid');
            }

            $this->handlers[$argument][] = $handler;
        }

        return $this;
    }

    public static function isArgument($value)
    {
        return (preg_match('/^(\+|-{1,2})[a-z][-_a-z0-9]*/i', $value) === 1);
    }

    protected function trigger(autodeploy\script $script)
    {
        $lastArgument = array_slice($this->values, -1);

        list($argument, $values) = each($lastArgument);

        if (isset($this->handlers[$argument]) === true)
        {
            $this->invokeHandlers($script, $argument, $values);
        }
        else
        {
            $argumentMetaphone = metaphone($argument);

            $min = null;
            $closestArgument = null;
            $handlerArguments = array_keys($this->handlers);

            natsort($handlerArguments);

            foreach ($handlerArguments as $handlerArgument)
            {
                $levenshtein = levenshtein($argumentMetaphone, metaphone($handlerArgument));

                if ($min === null || $levenshtein < $min)
                {
                    $min = $levenshtein;
                    $closestArgument = $handlerArgument;
                }
            }

            if ($closestArgument === null)
            {
                throw new \UnexpectedValueException('Argument \'' . $argument . '\' is unknown');
            }
            else if ($min > 0)
            {
                throw new \UnexpectedValueException('Argument \'' . $argument . '\' is unknown, did you mean \'' . $closestArgument . '\' ?');
            }
            else
            {
                $this->invokeHandlers($script, $closestArgument, $values);
            }
        }

        return $this;
    }

    protected function invokeHandlers(autodeploy\script $script, $argument, array $values)
    {
        foreach ($this->handlers[$argument] as $handler)
        {
            $handler->__invoke($script, $argument, $values, sizeof($this->values));
        }

        return $this;
    }
}
