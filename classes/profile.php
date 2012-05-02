<?php

namespace autodeploy;

class profile extends php\options
{

    protected $name = null;

    public function __construct()
    {

    }

    /**
     * @param string $name
     * @return profil
     */
    public function setName($name)
    {
        if (!is_string($name))
        {
            throw new \InvalidArgumentException();
        }

        $this->name = $name;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

}
