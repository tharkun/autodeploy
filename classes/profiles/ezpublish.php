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
                'active_extensions',
                'design_base',
                'module',
                'override',
                'translation',
                'template',
                'template_autoload',
                'autoload',
            ))
        ;

        return $this;
    }

}
