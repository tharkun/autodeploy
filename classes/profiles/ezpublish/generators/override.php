<?php

namespace autodeploy\frameworks\ezpublish\generators;

use
    autodeploy
;

class override extends autodeploy\generator
{

    public function __toString()
    {
        return \eZSys::cacheDirectory() . '/override/*';
    }

}
