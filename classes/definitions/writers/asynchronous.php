<?php

namespace autodeploy\definitions\writers;

use autodeploy\definitions;
use autodeploy\reports;

interface asynchronous extends definitions\writer
{

    /**
     * @param \autodeploy\reports\asynchronous $report
     * @return mixed
     */
    public function writeAsynchronous(reports\asynchronous $report);

}
