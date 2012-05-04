<?php

namespace autodeploy\scripts;

use autodeploy;
use autodeploy\step;
use autodeploy\factories;
use autodeploy\php\arguments;

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
                step::STEP_TRANSFORM => array(
                    function ($runner)
                    {
                        return factories\transformer::build(
                            step::defaultFactory,
                            $runner
                        );
                    },
                ),
                step::STEP_FILTER    => array(
                    function ($runner)
                    {
                        return factories\filter::build(
                            step::defaultFactory,
                            $runner
                        );
                    },
                ),
                step::STEP_PARSE     => array(
                    function ($runner)
                    {
                        return factories\parser::build(
                            step::defaultFactory,
                            $runner
                        );
                    }
                ),
                step::STEP_GENERATE  => array(
                    function ($runner, $task)
                    {
                        return factories\profile\generator::build(
                            array(
                                $runner->getProfile()->getName(),
                                'up'
                            ),
                            $runner,
                            $task['value']
                        );
                    }
                ),
                step::STEP_EXECUTE   => array(
                    function ($runner, $action)
                    {
                        return factories\task::build(
                            array(
                                str_replace('_', '\\', $action['type']),
                                $action['parser']
                            ),
                            $runner,
                            $action['command'],
                            $action['wildcard']
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
                foreach ($values as $value)
                {
                    $runner->getFilesIterator()->append( $value );
                }
            },
            array(''),
            arguments\parser::TYPE_MULTIPLE,
            ''
        );

        return $this;
    }

}
