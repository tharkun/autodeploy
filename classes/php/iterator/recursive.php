<?php

namespace autodeploy\php\iterator;

use autodeploy\php;

class recursive extends php\iterator implements \RecursiveIterator
{

    public function hasChildren()
    {
        return $this->current() instanceof php\iterator;
    }

    public function getChildren()
    {
        return $this->current();
    }

}
