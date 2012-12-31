<?php

namespace autodeploy\commands\svn;

use autodeploy;
use autodeploy\definitions\php\aggregatable;

class up extends autodeploy\command implements aggregatable
{

    /**
     * @return string
     */
    public function __toString()
    {
        return 'svn up ' . $this->getWildcard();
    }

    /**
     * @param \autodeploy\definitions\php\aggregatable $object
     * @return ezgeneratetranslationcache
     */
    public function aggregate(aggregatable $object)
    {
        $this->setWildcard( $this->getWildcard() . ' ' . $object->getWildcard() );

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