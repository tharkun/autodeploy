<?php

namespace autodeploy;

abstract class writer implements aggregators\php\adapter
{

    protected $adapter = null;

    public function __construct(php\adapter $adapter = null)
    {
        $this->setAdapter($adapter ?: new php\adapter());
    }

    public function setAdapter(php\adapter $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    public abstract function write($value);

    public abstract function clear();

}
