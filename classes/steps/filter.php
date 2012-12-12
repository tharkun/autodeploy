<?php

namespace autodeploy\steps;

use autodeploy\definitions;
use autodeploy\step;

class filter extends step implements definitions\php\observable
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

    /**
     * @return filter
     */
    public function runStep()
    {
        $this->getRunner()->getProfiles()->rewind();

        foreach ($this->getFactories() as $closure)
        {
            $filter = $closure->__invoke($this->getRunner());

            foreach ($this->observers as $observer)
            {
                $filter->addObserver($observer);
            }

            $filter->filter( $this->getRunner()->getIterator()->getChildren() );
        }

        return $this;
    }

}
