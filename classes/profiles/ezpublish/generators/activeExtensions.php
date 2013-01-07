<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class activeExtensions extends autodeploy\generator
{

    public function generate()
    {
        $command = new autodeploy\commands\delete\file( $this->getRunner() );
        $command->addWildcard( \eZExtension::CACHE_DIR . 'active_extensions_*' );

        return $command;
    }

}
