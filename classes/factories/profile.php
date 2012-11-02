<?php

namespace autodeploy\factories;

use autodeploy\step;
use autodeploy\php\factory;

class profile extends factory
{

    public static function instance()
    {
        $args = func_get_args();
        if (!is_array($args) || !count($args))
        {
            $args = array(step::defaultFactory);
        }

        return new static( $args );
    }

}
