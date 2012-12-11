<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class override extends autodeploy\generator
{

    public function generate()
    {
        return array(
            autodeploy\tasks\delete\folder::TYPE,
            \eZSys::cacheDirectory() . '/override/*'
        );
    }

}
