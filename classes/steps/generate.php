<?php

namespace autodeploy\steps;

use autodeploy\step;
use autodeploy\factories;
use autodeploy\factories\framework;

class generate extends step
{

    const runStart = 'stepGenerateStart';
    const runStop = 'stepGenerateStop';

    /**
     * @return string
     */
    public function getName()
    {
        return 'Generating actions';
    }

    /**
     * @return generate
     */
    public function runStep()
    {
        $tasksIterator = $this->getRunner()->getTasksIterator();
        foreach ($tasksIterator as $task)
        {
            foreach ($this->getFactories() as $oFactory)
            {
                $task['wildcard'] = (string) $oFactory->__invoke($this->getRunner(), $task);
                $tasksIterator->set($task);
            }
        }

        return $this;
    }

}
