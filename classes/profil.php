<?php

namespace autodeploy;

class profil extends php\options
{

    protected $name = null;

    /**
     * @param null $name
     */
    public function __construct($name = null)
    {
        $this->setName($name);
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
