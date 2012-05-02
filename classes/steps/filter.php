<?php

namespace autodeploy\steps;

use autodeploy\step;

class filter extends step
{

    const runStart = 'stepFilterStart';
    const runStop = 'stepFilterStop';

    /**
     * @return string
     */
    public function getName()
    {
        return 'Filtering input';
    }

    public function runStep()
    {
        foreach ($this->getFactories() as $oFactory)
        {
            $oFactory->__invoke($this->getRunner())->filter($this->getRunner()->getElementsIterator());
        }

        return $this;
    }

}
