<?php

namespace autodeploy\profiles\websync\generators;

use
    autodeploy
;

class main extends autodeploy\generator
{

    public function generate()
    {
        return array(
            "websync",
            $this->wildcard
        );
    }

}
