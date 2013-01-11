<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class override extends autodeploy\generator
{

    /**
     * @return \autodeploy\commands\delete\folder|void
     */
    public function generate()
    {
        $command = new autodeploy\commands\delete\folder( $this->getRunner() );
        $command->addWildcard( $command->cleanPath( \eZSys::cacheDirectory() . DIRECTORY_SEPARATOR . 'override' . DIRECTORY_SEPARATOR . '*' ) );

        return $command;
    }

}
