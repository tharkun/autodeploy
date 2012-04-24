<?php

namespace autodeploy\steps;

use autodeploy\step;
use autodeploy\factories;

class execute extends step
{

    const runStart = 'stepExecuteStart';
    const runStop = 'stepExecuteStop';

    const actionStart = 'actionStart';
    const actionStop = 'actionStop';

    /**
     * @return string
     */
    public function getName()
    {
        return 'Executing actions';
    }

    /**
     * @return execute
     */
    public function runStep()
    {
        $lastAction = '';

        foreach ($this->getRunner()->getTasksIterator() as $action)
        {
            if (($currentAction = md5( implode(':', array($action['parser'], $action['type'], $action['wildcard'])) )) == $lastAction && '' != $lastAction)
            {
                continue;
            }
            $lastAction = $currentAction;

            $this->callObservers(self::actionStart);

            factories\task::build(array(
                                       str_replace('_', '\\', $action['type']),
                                       $action['parser']
                                  ),
                                  $this->getRunner(),
                                  array($action['wildcard'])
            )
                    ->execute();

            $this->callObservers(self::actionStop);
        }

        return $this;
    }

}
