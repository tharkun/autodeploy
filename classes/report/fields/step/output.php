<?php

namespace autodeploy\report\fields\step;

use
    autodeploy,
    autodeploy\definitions\php\observable,
    autodeploy\php\locale,
    autodeploy\report\field
;

abstract class output extends field
{

    protected $output = null;

    /**
     * @param \autodeploy\php\locale $locale
     */
    public function __construct(locale $locale = null)
    {
        parent::__construct(array(autodeploy\steps\execute::actionStart, autodeploy\steps\execute::actionStop), $locale);
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
            if ($event === autodeploy\steps\execute::actionStart)
            {
                $this->output = null;
                ob_start();
            }
            else if ($event === autodeploy\steps\execute::actionStop)
            {
                $this->output = ob_get_clean() . "\n";
            }

            return true;
        }
    }

}
