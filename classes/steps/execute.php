<?php

namespace autodeploy\steps;

use autodeploy\step;

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
        $triggered = false;

        $iterator = $this->getRunner()->getTasksIterator();
        $iterator->rewind();

        $action = $iterator->current();

        $lastHashCommand = self::uniqueCommand($action);
        $lastHashAction  = self::uniqueAction($action);

        $groupAction = $action;
        $groupAction['wildcard'] = self::makeArray($groupAction['wildcard']);

        $iterator->next();

        while ($iterator->valid() === true)
        {
            $action = $iterator->current();

            $currentHashCommand = self::uniqueCommand($action);
            $currentHashAction  = self::uniqueAction($action);

            if ($currentHashAction == $lastHashAction)
            {
                $iterator->next();
                continue;
            }
            $lastHashAction = $currentHashAction;

            if ($currentHashCommand != $lastHashCommand)
            {
                $this->trigger($groupAction);
                $triggered = false;

                $lastHashCommand = $currentHashCommand;
                $groupAction = $action;
                $groupAction['wildcard'] = self::makeArray($groupAction['wildcard']);
            }
            else
            {
                $groupAction['wildcard'] = array_merge(
                    $groupAction['wildcard'],
                    self::makeArray($action['wildcard'])
                );
            }

            $iterator->next();
        }

        if (!$triggered)
        {
            $this->trigger($groupAction);
        }

        return $this;
    }

    public function trigger(array $action)
    {
        $this->callObservers(self::actionStart);

        foreach ($this->getFactories() as $closure)
        {
            $task = $closure->__invoke($this->getRunner(), $action);
            foreach ($this->observers as $observer)
            {
                $task->addObserver($observer);
            }

            $task->execute();
        }

        $this->callObservers(self::actionStop);

        return $this;
    }

    private static function uniqueCommand(array $action)
    {
        return md5( implode(':', array(
            //$action['parser'],
            $action['type'],
            $action['command'],
        )) );
    }

    private static function uniqueAction(array $action)
    {
        return md5( implode(':', array(
            $action['parser'],
            $action['type'],
            $action['command'],
            $action['wildcard']
        )) );
    }

    private static function makeArray($input)
    {
        return is_array($input) ? $input : array($input);
    }
}
