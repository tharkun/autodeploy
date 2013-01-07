<?php

namespace autodeploy\profiles\ezpublish\commands;

use autodeploy\commands\php;

class ezpgenerateautoloads extends php
{

    protected $options = array();

    /**
     * @return string
     */
    public function __toString()
    {
        return parent::__toString() . ' ' . $this->cleanPath('bin/php/ezpgenerateautoloads.php') . ' -e -p';
    }

}