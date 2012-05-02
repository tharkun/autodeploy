<?php

namespace autodeploy\profiles\svn\generators;

use
    autodeploy
;

class up extends autodeploy\generator
{

    public function generate()
    {
        return array(
            "svn up",
            $this->wildcard
        );
    }

}
