<?php

namespace autodeploy\steps;

use autodeploy\definitions;
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
        $this->getRunner()->getProfiles()->rewind();

        $iterator = $this->getRunner()->getIterator()->getChildren();

        foreach ($this->getFactories() as $closure)
        {
            $transformer = $closure->__invoke($this->getRunner());

            foreach ($this->observers as $observer)
            {
                $transformer->addObserver($observer);
            }

            $transformer->run($iterator);
        }

        $this->getRunner()->getIterator()->append($transformer->getIterator());

        return $this;
    }

}
