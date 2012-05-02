<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class template extends autodeploy\generator
{

    public function generate()
    {
        return \eZTemplateCompiler::compilationDirectory() . '/' . $this->wildcard . '-*';
    }

}
