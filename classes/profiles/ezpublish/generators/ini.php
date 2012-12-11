<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class ini extends autodeploy\generator
{

    public function generate()
    {
        return array(
            autodeploy\tasks\delete\file::TYPE,
            \eZINI::CONFIG_CACHE_DIR . $this->wildcard . '-*'
        );
    }

}
