<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class ini extends autodeploy\generator
{

    /**
     * @return \autodeploy\commands\delete\file|void
     */
    public function generate()
    {
        $command = new autodeploy\commands\delete\file( $this->getRunner() );
        $command->addWildcard( $command->cleanPath( \eZINI::CONFIG_CACHE_DIR . $this->wildcard . '-*' ) );

        return $command;
    }

}
