<?php

namespace autodeploy\profiles;

use autodeploy;
use autodeploy\step;

class simple extends autodeploy\profile
{

    public function init()
    {
        $this
            ->setName('simple')
            ->setParsers(array(
                step::defaultFactory,
            ))
        ;

        return $this;
    }

}
