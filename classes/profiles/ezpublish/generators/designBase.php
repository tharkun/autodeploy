<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class designBase extends autodeploy\generator
{

    public function generate()
    {
        $command = new autodeploy\commands\delete\file( $this->getRunner() );
        $command->addWildcard( \eZSys::cacheDirectory() . DIRECTORY_SEPARATOR . \eZTemplateDesignResource::DESIGN_BASE_CACHE_NAME.'*' );

        return $command;
    }

}
