<?php

namespace autodeploy\definitions\writers;

use autodeploy\reports;

interface synchronous
{

    public function writeSynchronous(reports\synchronous $report);

}
