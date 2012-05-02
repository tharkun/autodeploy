<?php

namespace autodeploy\steps;

use autodeploy\step;
use autodeploy\factories;
use autodeploy\factories\profile;

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

        foreach ($this->getFactories() as $oFactory)
        {
            foreach ($tasksIterator as $task)
            {
                $task['wildcard'] = (string) $oFactory->__invoke($this->getRunner(), $task);

                $tasksIterator->set($task);
            }
        }

        return $this;
    }

}
