<?php

namespace autodeploy\frameworks\ezpublish\generators;

use
    autodeploy
;

class ini extends autodeploy\generator
{

    public function __toString()
    {
        return \eZINI::CONFIG_CACHE_DIR . $this->wildcard . '-*';
    }

}