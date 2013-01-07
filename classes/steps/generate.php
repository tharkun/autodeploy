<?php

namespace autodeploy\steps;

use autodeploy\definitions;
use autodeploy\php;
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
        $iterator = new php\iterator();

        $tasksIterator = $this->getRunner()->getIterator()->getChildren();

        foreach ($this->getFactories() as $closure)
        {
            foreach ($tasksIterator as $task)
            {
                foreach ($this->getRunner()->getProfiles() as $profile)
                {
                    if ($task['profile'] != 'simple' && $profile->getName() !== $task['profile'])
                    {
                        continue;
                    }

                    $actions = $closure->__invoke($this->getRunner(), $task)->generate();

                    if (!is_object($actions) || !($actions instanceof php\iterator))
                    {
                        $actions = new php\iterator( array($actions) );
                    }

                    foreach ($actions as $return)
                    {
                        $action = $task;
                        if (is_object($return) && ($return instanceof php\options))
                        {
                            $action['type']     = 'execute_script';
                            //$action['command']  = $return->__isset('command') ? $return->command : 'auto';
                            //$action['wildcard'] = $return->__isset('wildcard') ? $return->wildcard : '';
                            //$action['grouped']  = $return->__isset('grouped') ? $return->grouped : false;
                            $action['todo']  = $return->__isset('todo') ? $return->todo : false;
                        }
                        /*else if (is_array($return) && 3 == count($return))
                        {
                            $action['type']     = $return[0];
                            $action['command']  = $return[1];
                            $action['wildcard'] = $return[2];
                            $action['grouped']  = false;
                        }
                        else if (is_array($return) && 2 == count($return))
                        {
                            $action['type']     = $return[0];
                            $action['command']  = 'auto';
                            $action['wildcard'] = $return[1];
                            $action['grouped']  = false;
                        }//*/
                        else
                        {
                            throw new \UnexpectedValueException();
                        }

                        $iterator->append($action);
                    }
                }
            }
        }

        $this->getRunner()->getIterator()->append( $iterator );

        return $this;
    }

}
