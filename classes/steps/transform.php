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
        $transformer = $this->getFactories()->current()->__invoke($this->getRunner());

        foreach ($this->observers as $observer)
        {
            $transformer->addObserver($observer);
        }

        $transformer->run($this->getRunner()->getFilesIterator());

        $this->getRunner()->setElementsIterator($transformer->getIterator());

        return $this;
    }

}
