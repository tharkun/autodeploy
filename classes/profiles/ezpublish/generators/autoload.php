<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class autoload extends autodeploy\generator
{

    public function generate()
    {
        $command = new autodeploy\commands\ezpgenerateautoloads( $this->getRunner() );

        return new autodeploy\php\options(array(
            'todo' => $command
        ));
        /*return new autodeploy\php\options(array(
            'type'      => autodeploy\tasks\execute\script::TYPE,
            'command'   => "php",
            'wildcard'  => 'bin/php/ezpgenerateautoloads.php -e -p',
            'grouped'   => false,
        ));//*/
    }

}
