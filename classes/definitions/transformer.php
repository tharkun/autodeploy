<?php

namespace autodeploy\definitions;

interface transformer
{

    /**
     * @abstract
     * @param $line
     * @return void
     */
    public function transform($line);

}
