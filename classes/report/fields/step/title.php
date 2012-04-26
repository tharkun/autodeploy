<?php

namespace autodeploy\report\fields\step;

use
    autodeploy,
    autodeploy\definitions\php\observable,
    autodeploy\php\locale,
    autodeploy\report\field
;

abstract class title extends field
{

    protected $stepNumber = null;
    protected $title = null;

    public function __construct(locale $locale = null)
    {
        parent::__construct(array(autodeploy\step::runStart), $locale);
    }

    public function handleEvent($event, observable $observable)
    {
        if (parent::handleEvent($event, $observable) === false)
        {
            return false;
        }
        else
        {
            $this->number = $observable->getRunner()->getStepNumber();
            $this->title = $observable->getName();

            return true;
        }
    }

}