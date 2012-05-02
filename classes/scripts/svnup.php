<?php

namespace autodeploy\scripts;

use autodeploy;
use autodeploy\factories;

final class svnup extends autodeploy\script implements autodeploy\aggregators\runner
{

    /**
     * @param $name
     * @param \autodeploy\runner|null $runner
     */
    public function __construct($name, autodeploy\runner $runner = null)
    {
        parent::__construct($name, $runner);

        $this->getRunner()
            ->setSteps(array(
                'transform' => array(
                    function ($runner)
                    {
                        return factories\transformer::build(
                            autodeploy\step::defaultFactory,
                            $runner
                        );
                    },
                ),
                'filter'    => array(
                    function ($runner)
                    {
                        return factories\filter::build(
                            autodeploy\step::defaultFactory,
                            $runner
                        );
                    },
                ),
                'parse'     => array(
                    function ($runner)
                    {
                        return factories\parser::build(
                            autodeploy\step::defaultFactory,
                            $runner
                        );
                    }
                ),
                'generate'  => array(
                    function ($runner, $task)
                    {
                        return factories\profile\generator::build(
                            array(
                                $runner->getProfil()->getName(),
                                'up'
                            ),
                            $runner,
                            $task['value']
                        );
                    }
                ),
                'execute'   => array(
                    function ($runner, $action)
                    {
                        return factories\task::build(
                            array(
                                str_replace('_', '\\', $action['type']),
                                $action['parser']
                            ),
                            $runner,
                            array($action['wildcard'])
                        );
                    }
                ),
            ))
        ;
    }

    protected function setArgumentHandlers()
    {
        $runner = $this->getRunner();

        $this->addArgumentHandler(
            function($script, $argument, $values) use ($runner)
            {
                if (!is_array($values))
                {
                    throw new \InvalidArgumentException(sprintf($script->getLocale()->_('Bad usage of %s, do php %s --help for more informations'), $argument, $script->getName()));
                }

                foreach ($values as $value)
                {
                    $runner->getFilesIterator()->append( $value );
                }
            },
            array(''),
            '',
            $this->locale->_('')
        );

        return $this;
    }

}
