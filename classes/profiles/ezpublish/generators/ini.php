<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class ini extends autodeploy\generator
{

    public function generate()
    {
        $command = new autodeploy\commands\delete\file( $this->getRunner() );
        $command->addWildcard( $command->cleanPath( \eZINI::CONFIG_CACHE_DIR . $this->wildcard . '-*' ) );

        return $command;
    }

}
