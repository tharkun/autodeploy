<?php

namespace autodeploy\profiles;

use autodeploy;
use autodeploy\step;

class simple extends autodeploy\profile
{

    /**
     * @return \autodeploy\profile|simple
     */
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
