<?php

namespace autodeploy\factories;

use autodeploy\php\factory;

class filter extends factory
{

    public function getPattern()
    {
        return 'filters\%s';
    }

}
