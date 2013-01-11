<?php

namespace autodeploy\php\iterator;

use autodeploy\php;

class recursive extends php\iterator implements \RecursiveIterator
{

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return $this->current() instanceof php\iterator;
    }

    /**
     * @return mixed|null|\RecursiveIterator
     */
    public function getChildren()
    {
        return $this->current();
    }

}
