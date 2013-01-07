<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class override extends autodeploy\generator
{

    public function generate()
    {
        $command = new autodeploy\commands\delete\folder( $this->getRunner() );
        $command->addWildcard(\eZSys::cacheDirectory() . '/override/*');

        return new autodeploy\php\options(array(
            //'type'      => autodeploy\tasks\delete\file::TYPE,
            //'wildcard'  => \eZSys::cacheDirectory() . '/override/*',
            //'grouped'   => true,
            'todo' => $command,
        ));

        /*return array(
            autodeploy\tasks\delete\folder::TYPE,
            \eZSys::cacheDirectory() . '/override/*'
        );//*/
    }

}
