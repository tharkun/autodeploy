<?php

namespace autodeploy\report\fields\step;

use
    autodeploy,
    autodeploy\definitions\php\observable,
    autodeploy\php\locale,
    autodeploy\report\field
;

abstract class actions extends field
{

    protected $parsers = array();
    protected $actions = array();

    public function __construct(locale $locale = null)
    {
        parent::__construct(array(autodeploy\steps\parse::runStop), $locale);
    }

    public function handleEvent($event, observable $observable)
    {
        if (parent::handleEvent($event, $observable) === false)
        {
            return false;
        }
        else
        {
            $this->parsers = $observable->getRunner()->getProfile()->getParsers();
            $this->actions = $observable->getRunner()->getTasksIterator();

            return true;
        }
    }

    public function getParsers()
    {
        return $this->parsers;
    }

    public function getActions()
    {
        return $this->actions;
    }

}
