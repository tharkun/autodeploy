<?php

namespace autodeploy\profiles;

use autodeploy;
use autodeploy\step;

class websync extends autodeploy\profile
{

    /**
     * @return \autodeploy\profile|websync
     */
    public function init()
    {
        $this
            ->setName('websync')
            ->setParsers(array(
                step::defaultFactory,
            ))
        ;

        return $this;
    }

}
