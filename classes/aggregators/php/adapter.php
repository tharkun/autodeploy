<?php

namespace autodeploy\aggregators\php;

use autodeploy\php;

interface adapter
{

    /**
     * @abstract
     * @param \autodeploy\php\adapter $adapter
     * @return void
     */
    public function setAdapter(php\adapter $adapter);

    /**
     * @abstract
     * @return void
     */
    public function getAdapter();

}
