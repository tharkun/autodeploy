<?php

namespace autodeploy\commands;

use autodeploy;

class svn extends autodeploy\command
{

    /**
     * @return string
     */
    public function __toString()
    {
        return 'svn';
    }

}