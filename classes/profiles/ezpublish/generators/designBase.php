<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class designBase extends autodeploy\generator
{

    public function generate()
    {
        return \eZSys::cacheDirectory() . '/' . \eZTemplateDesignResource::DESIGN_BASE_CACHE_NAME.'*';
    }

}
