<?php

namespace autodeploy\php;

class factory
{

    private $class = '';
    private $args = array();
    private $recursiveLevel = 0;

    /**
     * @param array $class
     * @param array $args
     */
    final protected function __construct(array $class, array $args = array())
    {
        $class = autoloader::normalize($class);
        array_unshift($class, $this->getPattern());
        $this->class = $class;
        $this->args  = $args;

        $this->recursiveLevel = substr_count($this->getPattern(), '%s\%s');
    }

    /**
     * @static
     * @return factory
     */
    public static function build()
    {
        $args = func_get_args();

        //print_r($args);

        $factory = new static(
            ($class = array_shift($args)) && is_string($class) ? array($class) : $class,
            $args
        );
        return $factory->create();
    }

    /**
     * @return void
     */
    final private function __clone() {}

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
     * @return
     */
    public function findRecursiveClassName($class, $level = 0)
    {
        if ($level > $this->recursiveLevel)
        {
            throw new \Exception('');
        }

        if (!class_exists($class) && preg_match('/.+\\\\.+/', $class))
        {
            $class = $this->findRecursiveClassName( implode('\\', array_slice(explode('\\', $class), 0, -1)), ++$level );
        }

        return $class;
    }

    /**
     * @return mixed|object
     */
    protected function create()
    {
        $namespace = str_replace('\php', '', __NAMESPACE__);

        $class = $this->findRecursiveClassName( $namespace . '\\' . call_user_func_array('sprintf', $this->class) );

        if ($namespace === $class)
        {
            throw new \RuntimeException('Factory is unable to create object');
        }

        $oReflectionClass = new \ReflectionClass( $class );

        $constructor = $oReflectionClass->getConstructor();
        if ($constructor->isPublic())
        {
            $object = $oReflectionClass->newInstanceArgs( $this->args );
        }
        else
        {
            if ($oReflectionClass->hasMethod('instance') && $oReflectionClass->getMethod('instance')->isStatic())
            {
                $object = call_user_func_array( $class.'::instance', $this->args );
            }
        }

        return $object;
    }

}
