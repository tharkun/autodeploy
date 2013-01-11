<?php

namespace autodeploy\factories\profile;

use autodeploy\factories;

class generator extends factories\profile
{

    /**
     * @return string
     */
    public function getPattern()
    {
        return 'profiles\%s\generators\%s';
    }

}
