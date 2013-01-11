<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class activeExtensions extends autodeploy\generator
{

    /**
     * @return \autodeploy\commands\delete\file|void
     */
    public function generate()
    {
        $command = new autodeploy\commands\delete\file( $this->getRunner() );
        $command->addWildcard( $command->cleanPath( \eZExtension::CACHE_DIR . 'active_extensions_*' ) );

        return $command;
    }

}
