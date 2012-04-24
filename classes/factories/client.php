<?php

namespace autodeploy\factories;

use autodeploy\php\factory;

class client extends factory
{

    public function getPattern()
    {
        return 'clients\%s';
    }

}
