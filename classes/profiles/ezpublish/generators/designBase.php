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
        $command->addWildcard( \eZSys::cacheDirectory() . '/' . \eZTemplateDesignResource::DESIGN_BASE_CACHE_NAME.'*' );

        return new autodeploy\php\options(array(
            'todo' => $command
        ));
        /*return array(
            autodeploy\tasks\delete\file::TYPE,
            \eZSys::cacheDirectory() . '/' . \eZTemplateDesignResource::DESIGN_BASE_CACHE_NAME.'*'
        );//*/
    }

}
