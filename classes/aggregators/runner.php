<?php

namespace autodeploy\aggregators;

use autodeploy;

interface runner
{

    public function setRunner(autodeploy\runner $runner);

    public function getRunner();

}
