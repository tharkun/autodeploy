<?php

namespace autodeploy\steps;

use autodeploy;
use autodeploy\factories;
use autodeploy\factories\framework;

class parse extends autodeploy\step
{

    const runStart = 'stepParseStart';
    const runStop = 'stepParseStop';

    /**
     * @return string
     */
    public function getName()
    {
        return 'Parsing input';
    }

    /**
     * @return parse
     */
    public function runStep()
    {
        $iterator = new autodeploy\iterator();

        foreach ($this->getRunner()->getProfil()->getParsers() as $parser)
        {
            $tasks = $this->getFactories()->current()->__invoke($this->getRunner(), $parser)
                ->parse( $this->getRunner()->getElementsIterator() )
                ->getTasks()
            ;

            foreach ($tasks as $task)
            {
                $iterator->append(array(
                    'parser' => $parser,
                    'type'   => $task[0],
                    'value'  => $task[1],
                ));
            }
        }

        $this->getRunner()->setTasksIterator($iterator);

        return $this;
    }

}
