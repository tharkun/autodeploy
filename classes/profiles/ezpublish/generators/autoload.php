<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class autoload extends autodeploy\generator
{

    public function generate()
    {
        return array(
            'php',
            'bin/php/ezpgenerateautoloads.php -e -p'
        );
    }

    public function getType()
    {
        return autodeploy\tasks\execute\script::TYPE;
    }

}
