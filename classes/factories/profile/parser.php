<?php

namespace autodeploy\factories\profile;

use autodeploy\factories;

class parser extends factories\profile
{

    /**
     * @return string
     */
    public function getPattern()
    {
        return 'profiles\%s\parsers\%s\%s';
    }

}
