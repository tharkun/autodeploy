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
        $command->addWildcard( \eZSys::cacheDirectory() . DIRECTORY_SEPARATOR . 'eztemplateautoload-*' );

        return new autodeploy\php\options(array(
            'todo' => $command
        ));
        /*return array(
            autodeploy\tasks\delete\file::TYPE,
            \eZSys::cacheDirectory() . '/eztemplateautoload-*'
        );//*/
    }

}
