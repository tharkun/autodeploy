<?php

namespace autodeploy\php;

class options implements \ArrayAccess
{

    protected $properties;

    /**
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        foreach ( $options as $option => $value )
        {
            $this->__set( $option, $value );
        }
    }

    /**
     * @param $name
     * @return mixed
     * @throws \UnexpectedValueException
     */
    public function __get($name)
    {
        if ( $this->__isset( $name ) === true )
        {
            return $this->properties[$name];
        }

        throw new \UnexpectedValueException( $name );
    }

    /**
     * @param $name
     * @param $value
     * @return options
     */
    public function __set($name, $value)
    {
        $this->properties[$name] = $value;

        return $this;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset( $this->properties[ $name ] );
    }

    /**
     * @param $name
     * @return bool
     */
    public function offsetExists($name)
    {
        return $this->__isset( $name );
    }

    /**
     * @param $name
     * @return mixed
     */
    public function offsetGet($name)
    {
        return $this->__get( $name );
    }

    /**
     * @param $name
     * @param $value
     */
    public function offsetSet($name, $value)
    {
        $this->__set( $name, $value );
    }

    /**
     * @param $name
     */
    public function offsetUnset( $name )
    {
        $this->__set( $name, null );
    }

    /**
     * @param $method
     * @param $args
     * @return options|mixed
     * @throws \BadMethodCallException
     */
    public function __call($method, $args)
    {
        preg_match('/^(g|s)et([A-Z][a-zA-Z0-9_]+)$/', $method, $matches);

        if (is_array($matches) && count($matches))
        {
            $name = strtolower(substr($matches[2], 0, 1)) . substr($matches[2], 1);
            if ('s' == $matches[1])
            {
                return $this->__set($name, $args[0]);
            }
            else if ('g' == $matches[1])
            {
                return $this->__get($name);
            }
        }

        throw new \BadMethodCallException( $name );
    }
}
