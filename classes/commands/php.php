<?php

namespace autodeploy\commands;

use autodeploy;

class php extends autodeploy\command
{

    private static $phpPath = 'php';

    /**
     * @return string
     */
    public function __toString()
    {
        return escapeshellarg(self::$phpPath);
    }

    /**
     * @param $phpPath
     */
    public static function setPhpPath($phpPath)
    {
        self::$phpPath = $phpPath;
    }

}