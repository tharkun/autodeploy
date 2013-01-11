<?php

namespace autodeploy\profiles;

use autodeploy;
use autodeploy\step;

class svn extends autodeploy\profile
{

    /**
     * @return \autodeploy\profile|svn
     */
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
