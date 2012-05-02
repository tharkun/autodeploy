<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class autoload extends autodeploy\generator
{

    public function generate()
    {
        return 'php bin/php/ezpgenerateautoloads.php -e';
    }

}
