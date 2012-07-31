<?php

namespace autodeploy;

class profile extends php\options
{

    protected $name = null;

    /**
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->init();
    }

    public function init()
    {
        return $this;
    }

    /**
     * @param $name
     * @return profile
     * @throws \InvalidArgumentException
     */
    final public function setName($name)
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
    final public function getName()
    {
        return $this->name;
    }

}
