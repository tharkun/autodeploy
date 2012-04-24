<?php

namespace autodeploy\factories;

use autodeploy\php\factory;

class task extends factory
{

    public function getPattern()
    {
        return 'tasks\%s\%s';
    }

}
