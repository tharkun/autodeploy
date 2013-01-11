<?php

namespace autodeploy\aggregators;

use autodeploy;

interface runner
{

    /**
     * @param \autodeploy\runner $runner
     * @return mixed
     */
    public function setRunner(autodeploy\runner $runner);

    /**
     * @return mixed
     */
    public function getRunner();

}
