<?php

namespace autodeploy\factories;

use autodeploy\php\factory;

class filter extends factory
{

    public function getPattern()
    {
        return 'profiles\%s\filter';
        return 'filters\%s';
    }

}
