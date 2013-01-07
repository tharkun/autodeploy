<?php

namespace autodeploy\commands\git;

use autodeploy\commands;
use autodeploy\definitions\php\aggregatable;

class pull extends commands\git
{

    /**
     * @return string
     */
    public function __toString()
    {
        return parent::__toString() . ' pull origin';
    }

}