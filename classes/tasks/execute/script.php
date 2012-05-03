<?php

namespace autodeploy\tasks\execute;

use autodeploy;

class script extends autodeploy\task
{

    const TYPE = 'execute_script';

    public function __toString()
    {
        return $this->command . ' ' . $this->getWildcardsAsString();
    }

}
