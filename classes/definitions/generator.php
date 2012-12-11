<?php

namespace autodeploy\definitions;

interface generator
{

    /**
     * @abstract
     * @return void
     */
    public function generate();

}
