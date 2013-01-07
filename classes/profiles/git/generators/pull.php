<?php

namespace autodeploy\profiles\git\generators;

use
    autodeploy
;

class pull extends autodeploy\generator
{

    public function generate()
    {
        $command = new autodeploy\commands\git\pull( $this->getRunner() );

        return $command;
    }

}
