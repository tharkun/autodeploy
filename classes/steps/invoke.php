<?php

namespace autodeploy\steps;

use autodeploy\step;

class invoke extends step
{

    const runStart = 'stepInvokeStart';
    const runStop = 'stepInvokeStop';

    /**
     * @return string
     */
    public function getName()
    {
        return 'Invoking';
    }

    /**
     * @return filter
     */
    public function runStep()
    {
        foreach ($this->getFactories() as $closure)
        {
            $closure->__invoke($this->getRunner());
        }

        return $this;
    }

}
