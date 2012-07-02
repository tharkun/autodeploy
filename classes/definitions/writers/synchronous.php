<?php

namespace autodeploy\definitions\writers;

use autodeploy\definitions;
use autodeploy\reports;

interface synchronous extends definitions\writer
{

    public function writeSynchronous(reports\synchronous $report);

}
