<?php

namespace autodeploy\steps;

use autodeploy\step;

class transform extends step
{

    const runStart = 'stepTransformStart';
    const runStop = 'stepTransformStop';

    /**
     * @return string
     */
    public function getName()
    {
        return 'Transforming input';
    }

    /**
     * @return transform
     */
    public function runStep()
    {
        $iterator = $this->getRunner()->getInputIterator();

        foreach ($this->getFactories() as $oFactory)
        {
            $transformer = $oFactory->__invoke($this->getRunner());

            foreach ($this->observers as $observer)
            {
                $transformer->addObserver($observer);
            }

            $transformer->run($iterator);
        }

        $this->getRunner()->setElementsIterator($transformer->getIterator());

        return $this;
    }

}
