<?php

namespace autodeploy\commands;

use autodeploy;

class git extends autodeploy\command
{

    /**
     * @return string
     */
    public function __toString()
    {
        return 'git';
    }

}