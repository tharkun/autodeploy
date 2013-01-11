<?php

namespace autodeploy\php;

require_once __DIR__ . '/../../constants.php';

final class autoloader
{

    protected static $instance = null;

    protected $roots = array();

    /**
     *
     */
    protected function __construct()
    {
        $this->roots[ str_replace('\php', '', __NAMESPACE__) ] = array( __DIR__ . '/../' );
    }

    /**
     * @static
     * @return null
     */
    public static function instance()
    {
        if (self::$instance === null)
        {
            self::$instance = new self();
            self::$instance->registerSpl();
        }

        return self::$instance;
    }

    /**
     * @throws \RuntimeException
     * @param bool $prepend
     * @param bool $throw
     * @return autoloader
     */
    public function registerSpl($prepend = true, $throw = true)
    {
        if (spl_autoload_register(array($this, 'load'), $throw, $prepend) === false)
        {
            throw new \RuntimeException('An error occured while attempting to add to spl autoload register');
        }

        return $this;
    }

    /**
     * @param $namespace
     * @param $directory
     * @return autoloader
     */
    public function addRoot($namespace, $directory)
    {
        if (!isset($this->roots[$namespace]))
        {
            $this->roots[$namespace] = array();
        }
        if (!isset($this->roots[$namespace][ md5($directory) ]))
        {
            $this->roots[$namespace][] = $directory;
        }

        return $this;
    }

    /**
     * @param $class
     * @return null|string
     */
    protected function getFilePath($class)
    {
        foreach ($this->roots as $namespace => $roots)
        {
            if ($class === $namespace || strpos($class, $namespace) !== 0)
            {
                continue;
            }
            foreach ($roots as $root)
            {
                if (is_file($path = $root . $this->cleanPath( str_replace($namespace, '', $class) ) . '.php'))
                {
                    return $path;
                }
            }
        }

        return null;

    }

    /**
     * @param $path
     * @return mixed
     */
    protected function cleanPath($path)
    {
        return str_replace('\\', DIRECTORY_SEPARATOR, $path);
    }

    /**
     * @param $class
     * @return void
     */
    protected function load($class)
    {
        if (!is_null($path = $this->getFilePath($class)))
        {
            require $path;
        }
    }

    /**
     * @param $mixed
     * @return array|string
     * @throws \InvalidArgumentException
     */
    public static function normalize($mixed)
    {
        if (is_array($mixed))
        {
            foreach ($mixed as $key => $class)
            {
                $mixed[ $key ] = self::normalize($class);
            }
        }
        else if (is_string($mixed))
        {
            if (strpos($mixed, '\\') !== false)
            {
                $mixed = implode('\\', self::normalize( explode('\\', $mixed) ));
            }
            else
            {
                $mixed = preg_replace('/[^a-zA-Z_]/', '_', $mixed);
                $mixed = trim($mixed, '_');
                $mixed = strtolower($mixed[0]) . substr( implode('', array_map('ucfirst', explode('_', $mixed))), 1);
            }
        }
        else
        {
            throw new \InvalidArgumentException('');
        }

        return $mixed;
    }

}

autoloader::instance();
