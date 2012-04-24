<?php

namespace autodeploy\steps;

use autodeploy\step;
use autodeploy\factories;

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
        factories\filter::build(
            $this->getRunner()->getProfil()->getOrigin(),
            $this->getRunner()
        )
                ->filter($this->getRunner()->getElementsIterator());

        factories\framework\filter::build(
            $this->getRunner()->getProfil()->getName(),
            $this->getRunner()
        )
                ->filter($this->getRunner()->getElementsIterator());

        return $this;
    }

}
