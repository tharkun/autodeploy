<?php

namespace autodeploy\commands;

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