<?php

namespace autodeploy\factories\profile;

use autodeploy\factories;

class filter extends factories\profile
{

    public function getPattern()
    {
        return 'profiles\%s\filter';
    }

}
