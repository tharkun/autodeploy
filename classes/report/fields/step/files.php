<?php

namespace autodeploy\report\fields\step;

use
    autodeploy,
    autodeploy\definitions\php\observable,
    autodeploy\php\locale,
    autodeploy\report\field
;

abstract class files extends field
{

    protected $files = array();

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
            $this->files = array();
            foreach ($observable->getRunner()->getIterator()->end()->prev()->getChildren() as $file)
            {
                $this->files[] = $file;
            }

            return true;
        }
    }

}
