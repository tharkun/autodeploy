<?php

namespace autodeploy\factories;

use autodeploy\php\factory;

class transformer extends factory
{

    public function getPattern()
    {
        return 'transformers\%s';
    }

}
