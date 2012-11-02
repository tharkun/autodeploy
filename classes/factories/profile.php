<?php

namespace autodeploy\factories;

use autodeploy;
use autodeploy\php;

class profile extends php\factory
{

    public static function instance()
    {
        $args = func_get_args();
        if (!is_array($args) || !count($args))
        {
            $args = array(autodeploy\step::defaultFactory);
        }

        return new static( $args );
    }

}
