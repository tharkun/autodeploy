<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class activeExtensions extends autodeploy\generator
{

    public function generate()
    {
        return array(
            autodeploy\tasks\delete\file::TYPE,
            \eZExtension::CACHE_DIR . 'active_extensions_*'
        );
    }

}
