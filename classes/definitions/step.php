<?php

namespace autodeploy\definitions;

interface step
{

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @return mixed
     */
    public function runStep();

}
