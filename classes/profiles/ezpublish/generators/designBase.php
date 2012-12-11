<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class designBase extends autodeploy\generator
{

    public function generate()
    {
        return array(
            autodeploy\tasks\delete\file::TYPE,
            \eZSys::cacheDirectory() . '/' . \eZTemplateDesignResource::DESIGN_BASE_CACHE_NAME.'*'
        );
    }

}
