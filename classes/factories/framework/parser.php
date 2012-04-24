<?php

namespace autodeploy\factories\framework;

use autodeploy\php\factory;

class parser extends factory
{

    public function getPattern()
    {
        return 'frameworks\%s\parsers\%s\%s';
    }

}
