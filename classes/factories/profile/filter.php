<?php

namespace autodeploy\factories\profile;

use autodeploy\factories;

class filter extends factories\profile
{

    /**
     * @return string
     */
    public function getPattern()
    {
        return 'profiles\%s\filter';
    }

}
