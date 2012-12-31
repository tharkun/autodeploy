<?php

namespace autodeploy\commands\delete;

use autodeploy;
use autodeploy\definitions\php\aggregatable;
use autodeploy\php;

class file extends autodeploy\command implements aggregatable
{

    protected $force = true;
    protected $verbose = true;
    protected $recursive = false;

    /**
     * @return string
     */
    public function __toString()
    {
        switch ($this->getRunner()->getSystem()->getOsType())
        {
            case php\system::OSTYPE_WIN:
            {
                $string = "del";
                if ($this->recursive)
                {
                    $string .= " /S";
                }
                if ($this->force)
                {
                    $string .= " /F";
                }
                return "$string " . $this->getWildcard();
                break;
            }
            case php\system::OSTYPE_UNIX:
            case php\system::OSTYPE_MAC:
            {
                $options = "";
                if ($this->recursive)
                {
                    $options .= " r";
                }
                if ($this->force)
                {
                    $options .= " f";
                }
                if ($this->verbose)
                {
                    $options .= " v";
                }
                return "rm" . ($options ? " -$options" : '') . $this->getWildcard();
                break;
            }
        }
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