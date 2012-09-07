<?php

namespace autodeploy\report\fields\step;

use
    autodeploy,
    autodeploy\definitions\php\observable,
    autodeploy\factories,
    autodeploy\php\locale,
    autodeploy\report\field
;

abstract class title extends field
{

    protected $currentStepNumber = null;
    protected $totalStepNumber   = null;

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
            $this->currentStepNumber    = $observable->getRunner()->getStepNumber();
            $this->totalStepNumber      = 0;

            $currentStepPosition = $observable->getRunner()->getSteps()->key();

            foreach ($observable->getRunner()->getSteps() as $step)
            {
                $factory = factories\step::instance($step['type']);
                if ($factory->getReflectionClass()->implementsInterface("autodeploy\\definitions\\php\\observable"))
                {
                    $this->totalStepNumber++;
                }
            }

            $observable->getRunner()->getSteps()->rewind()->next( $currentStepPosition );

            $this->title = $observable->getName();

            return true;
        }
    }

}
