<?php

namespace autodeploy\outputs;

class cli
{
    private static $isInteractive = null;

    public function __construct()
    {
        if (self::$isInteractive === null)
        {
            self::$isInteractive = (defined('STDOUT') === true && function_exists('posix_isatty') === true && posix_isatty(STDOUT) === true);
        }
    }

    public function isInteractive()
    {
        return self::$isInteractive;
    }

    public static function forceTerminal()
    {
        self::$isInteractive = true;
    }

}
