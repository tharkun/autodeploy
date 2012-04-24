<?php

namespace autodeploy;

abstract class generator implements definitions\generator
{

    protected $wildcard = null;

    /**
     * @param $wildcard
     */
    final public function __construct($wildcard)
    {
        $this->wildcard = $wildcard;
    }

    /**
     * @return void
     */
    final protected function __clone() {}

}
