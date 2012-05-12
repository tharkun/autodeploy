<?php

namespace autodeploy;

class profile extends php\options
{

    protected $name = null;

    /**
     * @param $name
     * @return profile
     * @throws \InvalidArgumentException
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
