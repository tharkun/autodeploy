<?php

namespace autodeploy\definitions;

use
    autodeploy
;

interface filter
{

    /**
     * Filter autodeploy\iterator
     * @param \autodeploy\filter\autodeploy\iterator|\autodeploy\iterator $oCachePurgeIterator
     * @return autodeploy\filterSvn|autodeploy\filterRsync
     */
    public function filter(autodeploy\iterator $oCachePurgeIterator);

}
