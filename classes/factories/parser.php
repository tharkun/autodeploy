<?php

namespace autodeploy\factories;

use autodeploy\php\factory;

class parser extends factory
{

    public function getPattern()
    {
        return 'parsers\%s';
    }

}
