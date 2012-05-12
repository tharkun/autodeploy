<?php

namespace autodeploy\steps;

use autodeploy\step;

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
     * @throws \UnexpectedValueException
     */
    public function runStep()
    {
        $tasksIterator = $this->getRunner()->getTasksIterator();

        foreach ($this->getFactories() as $oFactory)
        {
            foreach ($tasksIterator as $task)
            {
                $return = $oFactory->__invoke($this->getRunner(), $task)->generate();

                if (is_array($return) &&  2 == count($return))
                {
                    $task['command']  = $return[0];
                    $task['wildcard'] = $return[1];
                }
                else if (is_array($return) && 1 == count($return))
                {
                    $task['command']  = 'auto';
                    $task['wildcard'] = $return[0];
                }
                else if (is_string($return))
                {
                    $task['command']  = 'auto';
                    $task['wildcard'] = $return;
                }
                else
                {
                    throw new \UnexpectedValueException();
                }

                $tasksIterator->set($task);
            }
        }

        return $this;
    }

}
