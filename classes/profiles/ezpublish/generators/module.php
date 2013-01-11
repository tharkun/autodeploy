<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class module extends autodeploy\generator
{

    /**
     * @return \autodeploy\commands\delete\file|void
     */
    public function generate()
    {
        $command = new autodeploy\commands\delete\file( $this->getRunner() );
        $command->addWildcard( $command->cleanPath( \eZSys::cacheDirectory() . DIRECTORY_SEPARATOR . 'ezmodule-*' ) );

        return $command;
    }

}
