<?php

namespace autodeploy\steps;

use autodeploy\definitions;
use autodeploy\step;

class generate extends step implements definitions\php\observable
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
                $invoked = $closure->__invoke($this->getRunner(), $task);
                $return = $invoked->generate();

                $task['type'] = $invoked->getType();

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
