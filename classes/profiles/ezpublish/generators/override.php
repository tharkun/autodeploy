<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class override extends autodeploy\generator
{

    public function generate()
    {
        return \eZSys::cacheDirectory() . '/override/*';
    }

}
