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
        foreach ($this->getFactories() as $closure)
        {
            $filter = $closure->__invoke($this->getRunner());

            foreach ($this->observers as $observer)
            {
                $filter->addObserver($observer);
            }

            $filter->filter($this->getRunner()->getElementsIterator());
        }

        return $this;
    }

}
