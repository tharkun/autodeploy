<?php

namespace autodeploy\report\fields\step;

use
    autodeploy,
    autodeploy\definitions\php\observable,
    autodeploy\php\locale,
    autodeploy\report\field
;

abstract class memory extends field
{
    protected $consummedMemory = 0;
    protected $totalMemory = 0;

    public function __construct(locale $locale = null)
    {
        parent::__construct(array(autodeploy\step::runStop), $locale);
    }

    public function handleEvent($event, observable $observable)
    {
        if (parent::handleEvent($event, $observable) === false)
        {
            return false;
        }
        else
        {
            $this->consummedMemory  = $observable->getMemory();
            $this->totalMemory      = memory_get_usage(true);

            return true;
        }
    }
}

?>
