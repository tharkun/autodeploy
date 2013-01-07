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
            if (isset($action1['command']) && $action1['command'] instanceof definitions\php\aggregatable && $action['command']->isAggregatableWith($action1['command']))
            {
                $action['command']->aggregate($action1['command']);
                $triggered = false;
            }
            else
            {
                $this->trigger($action, $nextIterator);
                $action = $action1;
                $triggered = true;
            }
        }

        if (!$triggered)
        {
            $this->trigger($action, $nextIterator);
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

            if (1||trim($stdOut) !== '')
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
}
