<?php

namespace autodeploy\report\fields\step;

use
    autodeploy,
    autodeploy\definitions\php\observable,
    autodeploy\php\locale,
    autodeploy\report\field
;

abstract class elements extends field
{

    protected $values = array();

    public function __construct(locale $locale = null)
    {
        parent::__construct(array(autodeploy\steps\filter::runStop), $locale);
    }

    public function handleEvent($event, observable $observable)
    {
        if (parent::handleEvent($event, $observable) === false)
        {
            return false;
        }
        else
        {
            $this->values = array();
            foreach ($observable->getRunner()->getIterator()->end()->getChildren() as $element)
            {
                $this->values[] = $element->name;
            }

            return true;
        }
    }

}
