<?php

namespace autodeploy\frameworks\ezpublish\generators;

use
    autodeploy
;

class activeExtensions extends autodeploy\generator
{

    public function __toString()
    {
        return \eZExtension::CACHE_DIR . 'active_extensions_*';
    }

}
