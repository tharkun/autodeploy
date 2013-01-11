<?php

namespace autodeploy\php\arguments;

use autodeploy;

class parser implements \iteratorAggregate
{
    const TYPE_ALL = 0;
    const TYPE_NONE = 1;
    const TYPE_SINGLE = 2;
    const TYPE_MULTIPLE = 3;

    const OPTIONNAL = 0;
    const MANDATORY = 1;

    protected $superglobals = null;

    protected $values = array();
    private $handlers = array();

    /**
     * @param \autodeploy\php\superglobals $superglobals
     */
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
     * @param callable $handler
     * @param array $arguments
     * @param int $type
     * @param int $mandatory
     * @return parser
     * @throws \RuntimeException
     */
    public function addHandler(\closure $handler, array $arguments, $type = self::TYPE_ALL, $mandatory = self::OPTIONNAL)
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
                'closure'       => $handler,
                'arguments'     => $arguments,
                'type'          => $type,
                'mandatory'     => $mandatory,
                'values'        => array(),
            );
        }

        return $this;
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

        $argument = '';

        while ($arguments->valid() === true)
        {
            $value = $arguments->current();

            if (self::isOptionEndingCaracter($value) === true)
            {
                $argument = '';
            }
            else if (self::isOption($value) === false)
            {
                $this->values[$argument][] = $value;

                if ($this->handlers[$argument][0]['type'] == self::TYPE_SINGLE)
                {
                    $argument = '';
                }
            }
            else
            {
                $argument = $value;
                $this->isValidArgument($argument);

                $this->values[$argument] = array();

                if ($this->handlers[$argument][0]['type'] == self::TYPE_NONE)
                {
                    $argument = '';
                }
            }

            $arguments->next();
        }

        $this->trigger($script);

        $this->checkMandatory($script);

        return $this;
    }

    /**
     * @param $argument
     * @return bool
     * @throws \UnexpectedValueException
     */
    protected function isValidArgument($argument)
    {
        if (isset($this->handlers[$argument]) !== true)
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
        }

        return true;
    }

    /**
     * @static
     * @param $value
     * @return bool
     */
    public static function isOptionEndingCaracter($value)
    {
        return '--' === $value;
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
        foreach ($this->values as $argument => $values)
        {
            if ($this->isValidArgument($argument))
            {
                $this->invokeHandlers($script, $argument, $values);
            }
        }

        return $this;
    }

    /**
     * @param \autodeploy\script $script
     * @param $argument
     * @param array $values
     * @return parser
     * @throws \InvalidArgumentException
     */
    protected function invokeHandlers(autodeploy\script $script, $argument, array $values)
    {
        foreach ($this->handlers[$argument] as $i => $handler)
        {
            $isInvalid = false;
            switch ($handler['type'])
            {
                case self::TYPE_NONE:
                    $isInvalid = sizeof($values) != 0;
                    break;
                case self::TYPE_SINGLE:
                    $isInvalid = sizeof($values) != 1;
                    break;
                case self::TYPE_MULTIPLE:
                    $isInvalid = sizeof($values) <= 0;
                    break;
            }
            if ($isInvalid)
            {
                throw new \InvalidArgumentException(sprintf($script->getLocale()->_('Bad usage of %s, do php %s --help for more informations'), $argument, $script->getName()));
            }

            $this->handlers[$argument][$i]['values'] = $values;

            $handler['closure']->__invoke($script, $argument, $values, sizeof($this->values));
        }

        return $this;
    }

    /**
     * @param \autodeploy\script $script
     * @return parser
     * @throws \LogicException
     */
    protected function checkMandatory(autodeploy\script $script)
    {
        foreach ($this->handlers as $argument => $handlers)
        {
            foreach ($handlers as $handler)
            {
                if ($handler['mandatory'] == true && !count($handler['values']))
                {
                    throw new \LogicException(sprintf($script->getLocale()->_('Argument %s is mandatory, do php %s --help for more informations'), $argument, $script->getName()));
                }
            }
        }

        return $this;
    }

}
