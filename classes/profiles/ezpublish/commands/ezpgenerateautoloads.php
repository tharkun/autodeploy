<?php

namespace autodeploy\profiles\ezpublish\commands;

use autodeploy\commands\php;

class ezpgenerateautoloads extends php implements aggregatable
{

    protected $options = array();

    /**
     * @return string
     */
    public function __toString()
    {
        return parent::__toString() . ' ' . $this->cleanPath('bin/php/ezpgenerateautoloads.php') . ' -e -p';
    }

    /**
     * @param aggregatable $object
     * @return ezpgenerateautoloads
     */
    public function aggregate(aggregatable $object)
    {
        return $this;
    }

    /**
     * @param aggregatable $object
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