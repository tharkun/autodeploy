<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class translation extends autodeploy\generator
{

    public function __toString()
    {
        return \eZDir::path( array( \eZSys::cacheDirectory(), 'translation' ) ) . '/*';
    }

}
