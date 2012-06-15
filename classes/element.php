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

}
