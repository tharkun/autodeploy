<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class autoload extends autodeploy\generator
{

    /**
     * @return \autodeploy\profiles\ezpublish\commands\ezpgenerateautoloads|void
     */
    public function generate()
    {
        $command = new autodeploy\profiles\ezpublish\commands\ezpgenerateautoloads( $this->getRunner() );

        return $command;
    }

}
