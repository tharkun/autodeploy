<?php

namespace autodeploy;

class element
{

    private $stdClass = null;

    /**
     * @param \stdClass $oStdClass
     */
    public function __construct(\stdClass $oStdClass)
    {
        $this->stdClass = $oStdClass;
    }

    /**
     * @throws \InvalidArgumentException
     * @param $sProperty
     * @return mixed
     */
    public function __get( $sProperty )
    {
        if (isset($this->stdClass->$sProperty))
        {
            return $this->stdClass->$sProperty;
        }
        throw new \InvalidArgumentException( $sProperty );
    }

    /**
     * @return bool
     */
    public function isAdded()
    {
        return 'A' == $this->stdClass->action;
    }

    /**
     * @return bool
     */
    public function isDeleted()
    {
        return 'D' == $this->stdClass->action;
    }

    /**
     * @return bool
     */
    public function isUpdated()
    {
        return 'U' == $this->stdClass->action;
    }

    /**
     * @return bool
     */
    public function isConflict()
    {
        return 'C' == $this->stdClass->action;
    }

    /**
     * @return bool
     */
    public function isMerged()
    {
        return 'G' == $this->stdClass->action;
    }

    /**
     * @return bool
     */
    public function isExisted()
    {
        return 'E' == $this->stdClass->action;
    }

    /**
     * @return bool
     */
    public function isAddedOrDeleted()
    {
        return $this->isAdded() || $this->isDeleted();
    }
}
