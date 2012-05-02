<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy
;

class template extends autodeploy\generator
{

    public function __toString()
    {
        return \eZTemplateCompiler::compilationDirectory() . '/' . $this->wildcard . '-*';
    }

}
