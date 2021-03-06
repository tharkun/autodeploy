<?php

namespace autodeploy\profiles\websync\generators;

use
    autodeploy
;

class main extends autodeploy\generator
{

    /**
     * @return \autodeploy\php\options|void
     */
    public function generate()
    {
        return new autodeploy\php\options(array(
            'type'      => autodeploy\tasks\execute\script::TYPE,
            'command'   => "websync",
            'wildcard'  => $this->wildcard,
            'grouped'   => true,
        ));
    }

}
