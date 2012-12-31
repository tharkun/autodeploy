<?php

namespace autodeploy\steps;

use autodeploy;
use autodeploy\definitions;
use autodeploy\step;

class execute extends step implements definitions\php\observable
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

        $iterator = $this->getRunner()->getIterator()->getChildren();
        $iterator->rewind();

        $action = $iterator->current();

        $nextIterator = new autodeploy\php\iterator();

        while ($iterator->valid() === true)
        {
            $iterator->next();
            $action1 = $iterator->current();
            if ($action1['todo'] instanceof definitions\php\aggregatable && $action['todo']->isAggregatableWith($action1['todo']))
            {
                $action['todo']->aggregate($action1['todo']);
            }
            else
            {
                $this->trigger($action, $nextIterator);
                $action = $action1;
                $triggered = false;

            }
        }



        $this->getRunner()->getIterator()->append( $nextIterator );

        return $this;

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
                $this->trigger($groupAction, $nextIterator);
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
            $this->trigger($groupAction, $nextIterator);
        }

        $this->getRunner()->getIterator()->append( $nextIterator );

        return $this;
    }

    public function trigger(array $action, autodeploy\php\iterator $iterator)
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

            $stdOut = $task->getStdOut();

            if (trim($stdOut) !== '')
            {
                foreach (explode("\n", $stdOut) as $s)
                {
                    $iterator->append($s);
                }
            }
        }

        $this->callObservers(self::actionStop);

        return $this;
    }

    private static function uniqueCommand(array $action)
    {
        if (isset($action['grouped']) && $action['grouped'])
        {
            return md5( implode(':', array(
                $action['profile'],
                $action['type'],
                $action['command'],
            )) );
        }
        return md5( implode(':', array(
            $action['profile'],
            $action['type'],
            $action['command'],
            $action['wildcard'],
        )) );
    }

    private static function uniqueAction(array $action)
    {
        return md5( implode(':', array(
            $action['profile'],
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
