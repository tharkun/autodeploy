<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class templateAutoload extends autodeploy\generator
{

    public function generate()
    {
        return array(
            autodeploy\tasks\delete\file::TYPE,
            \eZSys::cacheDirectory() . '/eztemplateautoload-*'
        );
    }

}
