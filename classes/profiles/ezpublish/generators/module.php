<?php

namespace autodeploy\frameworks\ezpublish\generators;

use
    autodeploy
;

class module extends autodeploy\generator
{

    public function __toString()
    {
        return \eZSys::cacheDirectory() . '/ezmodule-*';
    }

}
