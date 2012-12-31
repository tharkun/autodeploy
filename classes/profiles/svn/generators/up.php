<?php

namespace autodeploy\profiles\svn\generators;

use
    autodeploy
;

class up extends autodeploy\generator
{

    public function generate()
    {
        $command = new autodeploy\commands\svn\up( $this->getRunner() );
        $command->setWildcard($this->wildcard);
        return new autodeploy\php\options(array(
            'type'      => autodeploy\tasks\execute\script::TYPE,
            'command'   => "svn up",
            'wildcard'  => $this->wildcard,
            'grouped'   => true,
            'todo' => $command
        ));
    }

}
