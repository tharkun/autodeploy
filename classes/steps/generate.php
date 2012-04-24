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
            $task['wildcard'] = (string)framework\generator::build(array(
                                                                        $this->getRunner()->getProfil()->getName(),
                                                                        $task['parser']
                                                                   ),
                                                                   $task['value']
            );
            $tasksIterator->set($task);
        }

        return $this;
    }

}
