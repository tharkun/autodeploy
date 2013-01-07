<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class module extends autodeploy\generator
{

    public function generate()
    {
        $command = new autodeploy\commands\delete\file( $this->getRunner() );
        $command->addWildcard( \eZSys::cacheDirectory() . DIRECTORY_SEPARATOR . 'ezmodule-*' );

        return $command;
    }

}
