<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class ini extends autodeploy\generator
{

    public function generate()
    {
        return \eZINI::CONFIG_CACHE_DIR . $this->wildcard . '-*';
    }

    public function getType()
    {
        return autodeploy\tasks\delete\file::TYPE;
    }

}
