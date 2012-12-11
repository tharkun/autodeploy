<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class templateAutoload extends autodeploy\generator
{

    public function generate()
    {
        return \eZSys::cacheDirectory() . '/eztemplateautoload-*';
    }

    public function getType()
    {
        return autodeploy\tasks\delete\file::TYPE;
    }

}
