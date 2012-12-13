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

        $parsers = array();
        foreach ($this->getRunner()->getProfiles() as $profile)
        {
            foreach ($profile->getParsers() as $name)
            {
                if (!in_array($name, $parsers))
                {
                    $parsers[] = $name;
                }
            }
        }

        foreach ($this->getFactories() as $closure)
        {
            foreach ($parsers as $name)
            {
                foreach ($this->getRunner()->getProfiles() as $profile)
                {
                    if (!in_array($name, $profile->getParsers()))
                    {
                        continue;
                    }

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
                            'profile'   => $profile->getName(),
                            'parser'    => $name,
                            'value'     => $task,
                        ));
                    }
                }
            }
        }

        $this->getRunner()->getIterator()->append( $iterator );

        return $this;
    }

}
