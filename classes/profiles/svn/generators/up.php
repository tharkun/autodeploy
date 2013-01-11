<?php

namespace autodeploy\profiles\svn\generators;

use
    autodeploy
;

class up extends autodeploy\generator
{

    /**
     * @return \autodeploy\commands\svn\up|void
     */
    public function generate()
    {
        $command = new autodeploy\commands\svn\up( $this->getRunner() );
        $command->addWildcard($this->wildcard);

        return $command;
    }

}
