<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class activeExtensions extends autodeploy\generator
{

    public function generate()
    {
        return \eZExtension::CACHE_DIR . 'active_extensions_*';
    }

    public function getType()
    {
        return autodeploy\tasks\delete\file::TYPE;
    }

}
