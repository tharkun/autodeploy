<?php

namespace autodeploy;

class profil
{

    const ORIGIN_SVN = 'svn';
    const ORIGIN_RSYNC = 'rsync';

    protected $name = null;
    protected $origin = null;
    protected $parsers = array();

    /**
     * @param null $name
     * @param null $origin
     * @param array $parsers
     * @return profil
     */
    public function __construct($name = null, $origin = null, array $parsers = array())
    {
        $this->setOrigin($origin);
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

    /**
     * Returns the list of origins
     * @return array
     */
    public function getOrigins()
    {
        return array(
            self::ORIGIN_SVN,
            self::ORIGIN_RSYNC,
        );
    }

    /**
     * Set the origin parameter
     * @throws \InvalidArgumentException
     * @param $origin
     * @return profil
     */
    public function setOrigin($origin)
    {
        if ($origin !== null && !in_array($origin, $this->getOrigins()))
        {
            throw new \InvalidArgumentException();
        }

        $this->origin = $origin;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param array $parsers
     * @return profil
     */
    public function setParsers(array $parsers)
    {
        $this->parsers = $parsers;

        return $this;
    }

    /**
     * @return array
     */
    public function getParsers()
    {
        return $this->parsers;
    }

}
