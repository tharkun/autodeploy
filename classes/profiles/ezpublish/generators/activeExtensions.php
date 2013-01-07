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

        return new autodeploy\php\options(array(
            'todo' => $command
        ));
        /*return array(
            autodeploy\tasks\delete\file::TYPE,
            \eZExtension::CACHE_DIR . 'active_extensions_*'
        );//*/
    }

}
