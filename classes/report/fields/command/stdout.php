<?php

namespace autodeploy\report\fields\command;

use
    autodeploy,
    autodeploy\definitions\php\observable,
    autodeploy\php\locale,
    autodeploy\report\field
;

abstract class stdout extends field
{
    protected $value = null;

    public function __construct(locale $locale = null)
    {
        parent::__construct(array(autodeploy\task::stdOutStart), $locale);
    }

    public function handleEvent($event, observable $observable)
    {
        if (parent::handleEvent($event, $observable) === false)
        {
            return false;
        }
        else
        {
            $this->value = $observable->getCurrentOutput();

            return true;
        }
    }
}

?>
