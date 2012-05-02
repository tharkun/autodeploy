<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class templateAutoload extends autodeploy\generator
{

    public function __toString()
    {
        return \eZSys::cacheDirectory() . '/eztemplateautoload-*';
    }

}
