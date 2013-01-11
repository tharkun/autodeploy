<?php

namespace autodeploy\report\fields\task;

use
    autodeploy,
    autodeploy\definitions\php\observable,
    autodeploy\php\locale,
    autodeploy\report\field
;

abstract class command extends field
{
    protected $value = null;

    /**
     * @param \autodeploy\php\locale $locale
     */
    public function __construct(locale $locale = null)
    {
        parent::__construct(array(autodeploy\task::taskStart), $locale);
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
            $this->value = (string) $observable;

            return true;
        }
    }
}
