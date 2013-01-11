<?php

namespace autodeploy\profiles\git\generators;

use
    autodeploy
;

class pull extends autodeploy\generator
{

    /**
     * @return \autodeploy\commands\git\pull|void
     */
    public function generate()
    {
        $command = new autodeploy\commands\git\pull( $this->getRunner() );

        return $command;
    }

}
