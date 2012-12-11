<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class module extends autodeploy\generator
{

    public function generate()
    {
        return array(
            autodeploy\tasks\delete\file::TYPE,
            \eZSys::cacheDirectory() . '/ezmodule-*'
        );
    }

}
