<?php

namespace autodeploy;

abstract class writer implements aggregators\php\adapter, aggregators\php\locale
{

    protected $adapter = null;
    protected $locale = null;

    /**
     * @param php\adapter|null $adapter
     * @param php\locale|null $locale
     */
    public function __construct(php\adapter $adapter = null, php\locale $locale = null)
    {
        $this->setAdapter($adapter ?: new php\adapter());
    }

    /**
     * @param php\adapter $adapter
     * @return writer
     */
    public function setAdapter(php\adapter $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * @return null
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param php\locale $locale
     * @return runner
     */
    public function setLocale(php\locale $locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return null
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param $value
     * @return writer
     */
    public function write($value)
    {
        return $this;
    }

    /**
     * @return writer
     */
    public function clear()
    {
        return $this;
    }
}
