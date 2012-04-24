<?php

namespace autodeploy\php;

class adapter
{

    /**
     * @param $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, array $args = array())
    {
        return call_user_func_array($method, $args);
    }

    /**
     * @param $function
     * @param array $args
     * @return mixed
     */
    public function invoke($function, array $args = array())
    {
        return call_user_func_array(array($this, $function), $args);
    }

}
