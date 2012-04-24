<?php

namespace autodeploy\factories\framework;

use autodeploy\php\factory;

class filter extends factory
{

    public function getPattern()
    {
        return 'frameworks\%s\filter';
    }

}
