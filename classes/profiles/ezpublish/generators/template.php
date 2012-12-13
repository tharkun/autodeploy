<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class template extends autodeploy\generator
{

    public function generate()
    {
        return new autodeploy\php\options(array(
            'type'      => autodeploy\tasks\delete\file::TYPE,
            'wildcard'  => \eZTemplateCompiler::compilationDirectory() . '/' . $this->wildcard . '-*',
            'grouped'   => true,
        ));
    }

}
