<?php

namespace autodeploy\profiles\svn\generators;

use
    autodeploy
;

class up extends autodeploy\generator
{

    public function __toString()
    {
        return sprintf("svn up %s", $this->wildcard);
    }

}
