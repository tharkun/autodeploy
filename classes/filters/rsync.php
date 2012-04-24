<?php

namespace autodeploy\filters;

use
    autodeploy
;

class rsync extends autodeploy\filter
{

    /**
     * @param \autodeploy\iterator $iterator
     * @return rsync
     */
    public function filter(autodeploy\iterator $iterator)
    {
        return $this;
    }

}
