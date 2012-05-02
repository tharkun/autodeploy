<?php

namespace autodeploy\factories\profile;

use autodeploy\php\factory;

class generator extends factory
{

    public function getPattern()
    {
        return 'profiles\%s\generators\%s';
    }

}
