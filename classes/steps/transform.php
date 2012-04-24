<?php

namespace autodeploy\steps;

use autodeploy\step;
use autodeploy\factories;

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
        $transformer = factories\transformer::build(
            $this->getRunner()->getProfil()->getOrigin(),
            $this->getRunner()
        );

        foreach ($this->observers as $observer)
        {
            $transformer->addObserver($observer);
        }

        $transformer->run($this->getRunner()->getFilesIterator());

        $this->getRunner()->setElementsIterator($transformer->getIterator());

        return $this;
    }

}
