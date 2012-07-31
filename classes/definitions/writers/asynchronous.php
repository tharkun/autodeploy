<?php

namespace autodeploy\definitions\writers;

use autodeploy\definitions;
use autodeploy\reports;

interface asynchronous extends definitions\writer
{

    public function writeAsynchronous(reports\asynchronous $report);

}
