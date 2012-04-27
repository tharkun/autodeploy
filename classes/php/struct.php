<?php

namespace autodeploy\php;

class struct
{

    /**
     * @param $name
     * @param $value
     * @throws \LogicException
     */
    final public function __set( $name, $value )
    {
        throw new \LogicException( $name );
    }

    /**
     * @param $name
     * @throws \LogicException
     */
    final public function __get( $name )
    {
        throw new \LogicException( $name );
    }

}
