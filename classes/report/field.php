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

    public function __construct(array $events = null, php\locale $locale = null)
    {
        $this->events = $events;
        $this->setLocale($locale ?: new php\locale());
    }

    public function setLocale(php\locale $locale)
    {
        $this->locale = $locale;

        return $this;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function getEvents()
    {
        return $this->events;
    }

    public function canHandleEvent($event)
    {
        return ($this->events === null ? true : in_array($event, $this->events));
    }

    public function handleEvent($event, observable $observable)
    {
        return $this->canHandleEvent($event);
    }

    abstract public function __toString();
}
