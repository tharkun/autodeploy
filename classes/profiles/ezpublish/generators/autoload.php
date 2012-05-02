<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class autoload extends autodeploy\generator
{

    public function __toString()
    {
        return 'php bin/php/ezpgenerateautoloads.php -e';
    }

}
