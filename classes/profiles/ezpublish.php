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
                'active_extensions',
                'translation',
                'module',
                'design_base',
                'template',
                'template_autoload',
                'autoload',
            ))
        ;

        return $this;
    }

}
