<?php

namespace autodeploy\definitions\writers;

use autodeploy\definitions;
use autodeploy\reports;

interface synchronous extends definitions\writer
{

    /**
     * @param \autodeploy\reports\synchronous $report
     * @return mixed
     */
    public function writeSynchronous(reports\synchronous $report);

}
