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
            autodeploy\tasks\execute\script::TYPE,
            'php',
            'bin/php/ezpgenerateautoloads.php -e -p'
        );
    }

}
