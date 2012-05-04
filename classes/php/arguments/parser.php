<?php

namespace autodeploy\php\arguments;

use autodeploy;

class parser implements \iteratorAggregate
{
    const TYPE_ALL = 0;
    const TYPE_NONE = 1;
    const TYPE_SINGLE = 2;
    const TYPE_MULTIPLE = 3;

    protected $superglobals = null;

    protected $values = array();
    private $handlers = array();

    public function __construct(autodeploy\php\superglobals $superglobals = null)
    {
        $this->setSuperglobals($superglobals ?: new autodeploy\php\superglobals());
    }

    /**
     * @param \autodeploy\php\superglobals $superglobals
     * @return parser
     */
    public function setSuperglobals(autodeploy\php\superglobals $superglobals)
    {
        $this->superglobals = $superglobals;

        return $this;
    }

    /**
     * @return null
     */
    public function getSuperglobals()
    {
        return $this->superglobals;
    }

    /**
     * @return parser
     */
    public function resetValues()
    {
        $this->values = array();

        return $this;
    }

    /**
     * @param null $argument
     * @return array|null
     */
    public function getValues($argument = null)
    {
        return ($argument === null ? $this->values : (isset($this->values[$argument]) === false ? null : $this->values[$argument]));
    }

    /**
     * @return \arrayIterator
     */
    public function getIterator()
    {
        return new \arrayIterator($this->getValues());
    }

    /**
     * @param \autodeploy\script $script
     * @param array $array
     * @return parser
     */
    public function parse(autodeploy\script $script, array $array = array())
    {
        if (sizeof($array) <= 0)
        {
            $array = array_slice($this->superglobals->_SERVER['argv'], 1);
        }

        $this->resetValues();

        if (sizeof($array) == 0)
        {
            return $this;
        }

        $arguments = new \arrayIterator($array);

        $value = $arguments->current();

        if (self::isOption($value) === false)
        {
            $argument = '';

            $this->values[$argument] = array($value);
        }
        else
        {
            $argument = $value;

            $this->values[$argument] = array();
        }

        $arguments->next();

        while ($arguments->valid() === true)
        {
            $value = $arguments->current();

            if (self::isOption($value) === false)
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

        return $this;
    }

    /**
     * @param closure $handler
     * @param array $arguments
     * @param $type
     * @return parser
     * @throws \RuntimeException
     */
    public function addHandler(\closure $handler, array $arguments, $type = self::TYPE_ALL)
    {
        $invoke = new \reflectionMethod($handler, '__invoke');

        if ($invoke->getNumberOfParameters() < 3)
        {
            throw new \RuntimeException('Handler must take three arguments');
        }

        foreach ($arguments as $argument)
        {
            if ('' != $argument && self::isOption($argument) === false)
            {
                throw new \RuntimeException('Argument \'' . $argument . '\' is invalid');
            }

            $this->handlers[$argument][] = array(
                'closure'   => $handler,
                'arguments' => $arguments,
                'type'      => $type,
            );
        }

        return $this;
    }

    /**
     * @static
     * @param $value
     * @return bool
     */
    public static function isOption($value)
    {
        return (preg_match('/^(\+|-{1,2})[a-z][-_a-z0-9]*/i', $value) === 1);
    }

    /**
     * @param \autodeploy\script $script
     * @return parser
     * @throws \UnexpectedValueException
     */
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

    /**
     * @param \autodeploy\script $script
     * @param $argument
     * @param array $values
     * @return parser
     */
    protected function invokeHandlers(autodeploy\script $script, $argument, array $values)
    {
        foreach ($this->handlers[$argument] as $handler)
        {
            switch ($handler['type'])
            {
                case self::TYPE_NONE:
                    if (sizeof($values) != 0)
                    {
                        throw new \InvalidArgumentException(sprintf($script->getLocale()->_('Bad usage of %s, do php %s --help for more informations'), $argument, $script->getName()));
                    }
                    break;
                case self::TYPE_SINGLE:
                    if (sizeof($values) != 1)
                    {
                        throw new \InvalidArgumentException(sprintf($script->getLocale()->_('Bad usage of %s, do php %s --help for more informations'), $argument, $script->getName()));
                    }
                    break;
                case self::TYPE_MULTIPLE:
                    if (sizeof($values) <= 0)
                    {
                        throw new \InvalidArgumentException(sprintf($script->getLocale()->_('Bad usage of %s, do php %s --help for more informations'), $argument, $script->getName()));
                    }
                    break;
            }

            $handler['closure']->__invoke($script, $argument, $values, sizeof($this->values));
        }

        return $this;
    }
}
