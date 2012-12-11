<?php

namespace autodeploy\definitions;

interface generator
{

    /**
     * @abstract
     * @return void
     */
    public function generate();

    /**
     * @abstract
     * @return void
     */
    public function getType();

}
