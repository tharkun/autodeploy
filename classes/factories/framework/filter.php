<?php

namespace autodeploy\factories\profile;

use autodeploy\php\factory;

class filter extends factory
{

    public function getPattern()
    {
        return 'profiles\%s\filter';
    }

}
