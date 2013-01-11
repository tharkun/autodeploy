<?php

namespace autodeploy\factories;

use autodeploy\php\factory;

class step extends factory
{

    /**
     * @return string
     */
    public function getPattern()
    {
        return 'steps\%s';
    }

}
