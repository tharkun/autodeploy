<?php

namespace autodeploy\php;

use autodeploy;

abstract class singleton
{

    protected static $instance = null;

    protected function __construct() {}

    /**
     * @throws \Exception
     */
    final public function __clone()
    {
        throw new \Exception('Cannot duplicate a singleton.');
    }

    /**
     * @static
     * @return mixed
     * @throws \Exception
     */
    public static function instance()
    {
        if (__CLASS__ == get_called_class())
        {
            throw new \Exception( sprintf('%s is an abstract class. Cannot instantiate via %s::%s().', __CLASS__, __CLASS__, __METHOD__) );
        }

        if (null === static::$instance)
        {
            $class = get_called_class();
            static::$instance = new $class();
            if (method_exists(static::$instance, 'init'))
            {
                static::$instance->init();
            }
        }

        if (self::$instance !== null)
        {
            throw new \Exception( sprintf("You MUST provide a <code>protected static \$_oInstance = null;</code> statement in %s class.", $class) );
        }

        return static::$instance;
    }

}
