<?php

namespace autodeploy\factories\profile;

use autodeploy\factories;

class transformer extends factories\profile
{

    public function getPattern()
    {
        return 'profiles\%s\transformer';
    }

}
