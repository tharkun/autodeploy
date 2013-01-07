<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class templateAutoload extends autodeploy\generator
{

    public function generate()
    {
        $command = new autodeploy\commands\delete\file( $this->getRunner() );
        $command->addWildcard( $command->cleanPath( \eZSys::cacheDirectory() . DIRECTORY_SEPARATOR . 'eztemplateautoload-*' ) );

        return $command;
    }

}
