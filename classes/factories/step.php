<?php

namespace autodeploy\factories;

use autodeploy\php\factory;

class step extends factory
{

    public function getPattern()
    {
        return 'steps\%s';
    }

}
