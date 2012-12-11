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

    public function getType()
    {
        return autodeploy\tasks\delete\folder::TYPE;
    }

}
