<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class module extends autodeploy\generator
{

    public function generate()
    {
        return \eZSys::cacheDirectory() . '/ezmodule-*';
    }

}
