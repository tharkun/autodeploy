<?php

namespace autodeploy\report;

use
    autodeploy,
    autodeploy\aggregators,
    autodeploy\definitions\php\observable,
    autodeploy\php
;

abstract class field implements aggregators\php\locale
{

    protected $events = array();
    protected $locale = null;

    /**
     * @param array $events
     * @param \autodeploy\php\locale $locale
     */
    public function __construct(array $events = null, php\locale $locale = null)
    {
        $this->events = $events;
        $this->setLocale($locale ?: new php\locale());
    }

    /**
     * @param \autodeploy\php\locale $locale
     * @return field|void
     */
    public function setLocale(php\locale $locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return null|void
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return array
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param $event
     * @return bool
     */
    public function canHandleEvent($event)
    {
        return ($this->events === null ? true : in_array($event, $this->events));
    }

    /**
     * @param $event
     * @param \autodeploy\definitions\php\observable $observable
     * @return bool
     */
    public function handleEvent($event, observable $observable)
    {
        return $this->canHandleEvent($event);
    }

    abstract public function __toString();
}
