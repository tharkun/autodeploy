<?php

namespace autodeploy\filters;

use
    autodeploy
;

class basic extends autodeploy\filter
{

    /**
     * @param \autodeploy\iterator $iterator
     * @return none
     */
    public function filter(autodeploy\iterator $iterator)
    {
        return $this;
    }

}
