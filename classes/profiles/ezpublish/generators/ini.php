<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class ini extends autodeploy\generator
{

    public function generate()
    {
        $command = new autodeploy\commands\delete\file( $this->getRunner() );
        $command->addWildcard(\eZINI::CONFIG_CACHE_DIR . $this->wildcard . '-*');

        return new autodeploy\php\options(array(
            'todo' => $command,
        ));
        /*return new autodeploy\php\options(array(
            'type'      => autodeploy\tasks\delete\file::TYPE,
            'wildcard'  => \eZINI::CONFIG_CACHE_DIR . $this->wildcard . '-*',
            'grouped'   => true,
        ));//*/
    }

}
