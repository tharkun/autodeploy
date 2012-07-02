<?php

namespace autodeploy\php\sapi;

use autodeploy\php;

class cli
{

    private static $isInteractive = null;

    protected $system = null;

    private $positionStored = false;

    /**
     * @param \autodeploy\php\system|null $system
     */
    public function __construct(php\system $system = null)
    {
        if (self::$isInteractive === null)
        {
            self::$isInteractive = (defined('STDOUT') === true && function_exists('posix_isatty') === true && posix_isatty(STDOUT) === true);
        }
    }

    /**
     * @return bool|null
     */
    public function isInteractive()
    {
        return self::$isInteractive;
    }

    /**
     * @static
     */
    public static function forceTerminal()
    {
        self::$isInteractive = true;
    }

    /**
     * @param \autodeploy\php\system|null $system
     * @return cli
     */
    public function setSystem(php\system $system = null)
    {
        $this->system = $system;

        return $this;
    }

    /**
     * @return null
     */
    public function getSystem()
    {
        return $this->system;
    }

    /**
     * @return cli
     */
    public function storePosition()
    {
        if ( $this->system->getOsType() !== php\system::OSTYPE_WIN )
        {
            echo "\0337";
            $this->positionStored = true;
        }

        return $this;
    }

    /**
     * @return cli
     * @throws \RuntimeException
     */
    public function restorePosition()
    {
        if ( $this->system->getOsType() !== php\system::OSTYPE_WIN )
        {
            if ( $this->positionStored === false )
            {
                throw new \RuntimeException();
            }
            echo "\0338";
        }

        return $this;
    }
}
