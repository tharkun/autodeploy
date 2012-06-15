<?php

namespace autodeploy\factories\profile;

use autodeploy\php\factory;

class transformer extends factory
{

    public function getPattern()
    {
        return 'profiles\%s\transformer';
    }

}
