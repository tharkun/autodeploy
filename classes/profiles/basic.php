<?php

namespace autodeploy\profiles;

use autodeploy;
use autodeploy\step;

class basic extends autodeploy\profile
{

    public function init()
    {
        $this
            ->setName('basic')
            ->setParsers(array(
                step::defaultFactory,
            ))
        ;

        return $this;
    }

}
