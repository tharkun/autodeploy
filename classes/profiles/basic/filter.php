<?php

namespace autodeploy\profiles\basic;

use
    autodeploy
;

class filter extends autodeploy\filter
{

    /**
     * @param \autodeploy\php\iterator $iterator
     * @return basic
     */
    public function filter(autodeploy\php\iterator $iterator)
    {
        return $this;
    }

}
