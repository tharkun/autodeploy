<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class template extends autodeploy\generator
{

    public function generate()
    {
        return array(
            autodeploy\tasks\delete\file::TYPE,
            \eZTemplateCompiler::compilationDirectory() . '/' . $this->wildcard . '-*'
        );
    }

}
