<?php

namespace autodeploy\profiles\git;

use
    autodeploy
;

class filter extends autodeploy\filter
{

    /**
     * @param \autodeploy\php\iterator $iterator
     * @return simple
     */
    public function filter(autodeploy\php\iterator $iterator)
    {
        return $this;
    }

}
