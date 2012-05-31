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
        $tasksIterator = $this->getRunner()->getIterator()->getChildren();

        foreach ($this->getFactories() as $closure)
        {
            foreach ($tasksIterator as $task)
            {
                $return = $closure->__invoke($this->getRunner(), $task)->generate();

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
