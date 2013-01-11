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

    /**
     * @param \autodeploy\php\locale $locale
     */
    public function __construct(locale $locale = null)
    {
        parent::__construct(array(autodeploy\steps\parse::runStop), $locale);
    }

    /**
     * @param $event
     * @param \autodeploy\definitions\php\observable $observable
     * @return bool
     */
    public function handleEvent($event, observable $observable)
    {
        if (parent::handleEvent($event, $observable) === false)
        {
            return false;
        }
        else
        {
            $this->parsers = $observable->getRunner()->getProfiles()->rewind()->current()->getParsers();
            //$this->actions = $observable->getRunner()->getTasksIterator();
            $this->actions = $observable->getRunner()->getIterator()->end()->getChildren();

            return true;
        }
    }

    /**
     * @return array
     */
    public function getParsers()
    {
        return $this->parsers;
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

}
