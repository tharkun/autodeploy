<?php

namespace autodeploy\filters;

use
    autodeploy
;

class basic extends autodeploy\filter
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
