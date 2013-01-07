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
        $command->addWildcard( \eZTemplateCompiler::compilationDirectory() . '/' . $this->wildcard . '-*' );

        return new autodeploy\php\options(array(
            'todo' => $command
        ));
        /*return new autodeploy\php\options(array(
            'type'      => autodeploy\tasks\delete\file::TYPE,
            'wildcard'  => \eZTemplateCompiler::compilationDirectory() . '/' . $this->wildcard . '-*',
            'grouped'   => true,
        ));//*/
    }

}
