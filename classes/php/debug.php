<?php

namespace autodeploy\php;

use autodeploy;

class debug extends singleton
{

    const LEVEL_NOTICE  = 1;
    const LEVEL_WARNING = 2;
    const LEVEL_ERROR   = 3;
    const LEVEL_STRICT  = 4;

    protected static $instance = null;

    private $recursive = false;

    private static $errors = array(
        E_ERROR             => 'E_ERROR',
        E_PARSE             => 'E_PARSE',
        E_CORE_ERROR        => 'E_CORE_ERROR',
        E_COMPILE_ERROR     => 'E_COMPILE_ERROR',
        E_USER_ERROR        => 'E_USER_ERROR',
        E_WARNING           => 'E_WARNING',
        E_CORE_WARNING      => 'E_CORE_WARNING',
        E_COMPILE_WARNING   => 'E_COMPILE_WARNING',
        E_USER_WARNING      => 'E_USER_WARNING',
        E_NOTICE            => 'E_NOTICE',
        E_USER_NOTICE       => 'E_USER_NOTICE',
        E_STRICT            => 'E_STRICT',
    );


    protected function init()
    {
        // Since PHP 5.2
        if (defined('E_RECOVERABLE_ERROR'))
        {
            self::$errors[E_RECOVERABLE_ERROR] = 'E_RECOVERABLE_ERROR';
        }

        // Since PHP 5.3
        if (defined('E_DEPRECATED'))
        {
            self::$errors[E_DEPRECATED] = 'E_DEPRECATED';
        }
        if (defined('E_USER_DEPRECATED'))
        {
            self::$errors[E_USER_DEPRECATED] = 'E_USER_DEPRECATED';
        }

        restore_error_handler();
        set_error_handler( array( $this, 'protectRecursive' ) );
    }

    public function protectRecursive($errno, $errstr, $errfile, $errline)
    {
        if ( $this->recursive )
        {
            $this->recursive = false;
            return;
        }

        $this->recursive = true;
        $this->handleError($errno, $errstr, $errfile, $errline);
        $this->recursive = false;
    }

    private function handleError($errno, $errstr, $errfile, $errline)
    {
        if ( error_reporting() == 0 )
        {
            return false;
        }

        $str = "$errstr in $errfile on line $errline";

        $errname = "Unknown error code ($errno)";
        if (isset(self::$errors[$errno]))
        {
            $errname = self::$errors[$errno];
        }

        switch ( $errname )
        {
            case 'E_ERROR':
            case 'E_PARSE':
            case 'E_CORE_ERROR':
            case 'E_COMPILE_ERROR':
            case 'E_USER_ERROR':
            case 'E_RECOVERABLE_ERROR':
            case 'E_WARNING':
            case 'E_CORE_WARNING':
            case 'E_COMPILE_WARNING':
            case 'E_USER_WARNING':
            case 'E_DEPRECATED':
            case 'E_USER_DEPRECATED':
            case 'E_NOTICE':
            case 'E_USER_NOTICE':
            case 'E_STRICT':
            default:
                echo sprintf("PHP: %s : %s\n", $errname, $str);
                break;
        }
    }
}
