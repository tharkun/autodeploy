<?php

namespace autodeploy\frameworks\ezpublish\generators;

use
    autodeploy
;

class designBase extends autodeploy\generator
{

    public function __toString()
    {
        return \eZSys::cacheDirectory() . '/' . \eZTemplateDesignResource::DESIGN_BASE_CACHE_NAME.'*';
    }

}
