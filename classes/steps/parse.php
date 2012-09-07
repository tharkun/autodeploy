<?php

namespace autodeploy\steps;

use autodeploy\definitions;
use autodeploy;

class parse extends autodeploy\step implements definitions\php\observable
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
        $iterator = new autodeploy\php\iterator();

        foreach ($this->getFactories() as $closure)
        {
            foreach ($this->getRunner()->getProfile()->getParsers() as $name)
            {
                $parser = $closure->__invoke($this->getRunner(), $name);
                foreach ($this->observers as $observer)
                {
                    $parser->addObserver($observer);
                }

                $tasks = $parser
                    ->parse( $this->getRunner()->getIterator()->getChildren() )
                    ->getTasks()
                ;

                foreach ($tasks as $task)
                {
                    $iterator->append(array(
                        'parser' => $name,
                        'type'   => $task[0],
                        'value'  => $task[1],
                    ));
                }
            }
        }

        $this->getRunner()->getIterator()->append( $iterator );

        return $this;
    }

}
