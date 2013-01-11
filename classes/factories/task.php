<?php

namespace autodeploy\factories;

use autodeploy\php\factory;

class task extends factory
{

    /**
     * @return string
     */
    public function getPattern()
    {
        return 'tasks\%s\%s';
    }

}
