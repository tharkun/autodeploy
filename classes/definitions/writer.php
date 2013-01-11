<?php

namespace autodeploy\definitions;

interface writer
{

    /**
     * @param $value
     * @return mixed
     */
    public function write($value);

    /**
     * @return mixed
     */
    public function clear();

}
