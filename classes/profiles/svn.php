<?php

namespace autodeploy\profiles;

use autodeploy;
use autodeploy\step;

class svn extends autodeploy\profile
{

    public function init()
    {
        $this
            ->setName('svn')
            ->setParsers(array(
                step::defaultFactory,
            ))
        ;

        return $this;
    }

}
