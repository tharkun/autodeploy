<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class template extends autodeploy\generator
{

    /**
     * @return \autodeploy\commands\delete\file|void
     */
    public function generate()
    {
        $command = new autodeploy\commands\delete\file( $this->getRunner() );
        $command->addWildcard( $command->cleanPath( \eZTemplateCompiler::compilationDirectory() . DIRECTORY_SEPARATOR . $this->wildcard . '-*' ) );

        return $command;
    }

}
