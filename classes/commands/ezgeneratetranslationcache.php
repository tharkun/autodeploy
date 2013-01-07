<?php

namespace autodeploy\commands;

use autodeploy\definitions\php\aggregatable;

class ezgeneratetranslationcache extends php implements aggregatable
{

    /**
     * @return string
     */
    public function __toString()
    {
        return parent::__toString() . ' ' . $this->cleanPath('bin/php/ezgeneratetranslationcache.php') . ' --ts-list="'.implode(' ', $this->getWildcards()).'"';
    }

    /**
     * @param \autodeploy\definitions\php\aggregatable $object
     * @return ezgeneratetranslationcache
     */
    public function aggregate(aggregatable $object)
    {
        $this->addWildcard( $object->getWildcards() );

        return $this;
    }

    /**
     * @param \autodeploy\definitions\php\aggregatable $object
     * @return bool
     */
    public function isAggregatableWith(aggregatable $object)
    {
        if (get_class($object) === get_class($this))
        {
            return true;
        }

        return false;
    }

}