<?php

namespace autodeploy\profiles;

use autodeploy;
use autodeploy\step;

class git extends autodeploy\profile
{

    /**
     * @return \autodeploy\profile|git
     */
    public function init()
    {
        $this
            ->setName('git')
            ->setParsers(array(
                step::defaultFactory,
            ))
        ;

        return $this;
    }

}
