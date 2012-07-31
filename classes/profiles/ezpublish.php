<?php

namespace autodeploy\profiles;

use autodeploy;
use autodeploy\step;

class ezpublish extends autodeploy\profile
{

    public function init()
    {
        $this
            ->setName('ezpublish')
            ->setParsers(array(
                'ini',
                'override',
                'template',
                'template_autoload',
                'module',
                'translation',
                'design_base',
                'active_extensions',
                'autoload',
            ))
        ;

        return $this;
    }

}
