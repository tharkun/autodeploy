<?php

namespace autodeploy\php;

class factory
{

    private $reflectionClass = null;

    private $args = array();

    /**
     * @param array $array
     */
    final protected function __construct(array $array)
    {
        $array = $class = autoloader::normalize($array);
        array_unshift($class, $this->getPattern());

        for ($i = 0; $i < substr_count($this->getPattern(), '%s') - count($array)+1; $i++)
        {
            $class[] = '';
        }

        $this->reflectionClass = new \ReflectionClass( $this->findRecursiveClassName( str_replace('\php', '', __NAMESPACE__) . '\\' . call_user_func_array('sprintf', $class) ) );
    }

    /**
     * @return factory
     */
    public static function instance()
    {
        return new static( func_get_args() );
    }

    /**
     * @throws \LogicException
     */
    final public function __clone()
    {
        throw new \LogicException( sprintf('Class %s can not be cloned.', __CLASS__) );
    }

    /**
     * @return \ReflectionClass
     */
    public function getReflectionClass()
    {
        return $this->reflectionClass;
    }

    /**
     * @return factory
     */
    final public function with()
    {
        $this->args = func_get_args();

        return $this;
    }

    /**
     * @return mixed|object
     */
    final public function make()
    {
        $constructor = $this->reflectionClass->getConstructor();
        if ($constructor->isPublic())
        {
            $object = $this->reflectionClass->newInstanceArgs( $this->args );
        }
        else
        {
            if ($this->reflectionClass->hasMethod('instance') && $this->reflectionClass->getMethod('instance')->isStatic())
            {
                $object = call_user_func_array( $this->reflectionClass->getName().'::instance', $this->args );
            }
        }

        return $object;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return '%s';
    }

    /**
     * @param $class
     * @param int $level
     * @return string
     * @throws \RuntimeException
     */
    public function findRecursiveClassName($class, $level = 0)
    {
        if ($level > 5)
        {
            throw new \RuntimeException();
        }

        if (!class_exists($class))
        {
            if ('s' == $class[ strlen($class)-1 ] && class_exists( substr($class, 0, -1) ))
            {
                $class = substr($class, 0, -1);
            }
        }

        if (!class_exists($class) && preg_match('/.+\\\\.+/', $class))
        {
            $class = $this->findRecursiveClassName( implode('\\', array_slice(explode('\\', $class), 0, -1)), ++$level );
        }

        return $class;
    }

}
