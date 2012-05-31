<?php

namespace autodeploy\definitions;

use autodeploy\php;

interface filter
{

    /**
     * @abstract
     * @param \autodeploy\php\iterator $iterator
     * @return mixed
     */
    public function filter(php\iterator $iterator);

}
