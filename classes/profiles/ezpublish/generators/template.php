<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class template extends autodeploy\generator
{

    public function generate()
    {
        $command = new autodeploy\commands\delete\file( $this->getRunner() );
        $command->addWildcard( $command->cleanPath( \eZTemplateCompiler::compilationDirectory() . DIRECTORY_SEPARATOR . $this->wildcard . '-*' ) );

        return $command;
    }

}
