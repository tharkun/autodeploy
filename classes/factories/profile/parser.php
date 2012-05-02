<?php

namespace autodeploy\factories\profile;

use autodeploy\php\factory;

class parser extends factory
{

    public function getPattern()
    {
        return 'profiles\%s\parsers\%s\%s';
    }

}
