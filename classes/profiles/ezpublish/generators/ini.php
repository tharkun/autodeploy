<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class ini extends autodeploy\generator
{

    public function generate()
    {
        return new autodeploy\php\options(array(
            'type'      => autodeploy\tasks\delete\file::TYPE,
            'wildcard'  => \eZINI::CONFIG_CACHE_DIR . $this->wildcard . '-*',
            'grouped'   => true,
        ));
    }

}
