<?php

namespace autodeploy\profiles\simple;

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
