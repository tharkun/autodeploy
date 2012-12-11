<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class translation extends autodeploy\generator
{

    public function generate()
    {
        return \eZDir::path( array( \eZSys::cacheDirectory(), 'translation' ) ) . '/*';
    }

    public function getType()
    {
        return autodeploy\tasks\delete\folder::TYPE;
    }

}
