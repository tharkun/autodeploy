<?php

namespace autodeploy\factories\framework;

use autodeploy\php\factory;

class generator extends factory
{

    public function getPattern()
    {
        return 'frameworks\%s\generators\%s';
    }

}
