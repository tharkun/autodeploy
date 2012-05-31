<?php

namespace autodeploy\scripts;

use autodeploy;
use autodeploy\step;
use autodeploy\factories;
use autodeploy\php\arguments;

final class svnup extends autodeploy\script
{

    protected function setArgumentHandlers()
    {
        $runner = $this->getRunner();

        $this->addArgumentHandler(
            function($script, $argument, $values) use ($runner)
            {
                foreach ($values as $value)
                {
                    //$runner->getInputIterator()->append( $value );
                    $runner->getIterator()->getChildren()->append( $value );
                }
            },
            array(''),
            arguments\parser::TYPE_MULTIPLE,
            ''
        );

        return $this;
    }

    protected function setStepHandlers()
    {
        $this->getRunner()
            ->addStep(step::STEP_TRANSFORM, array(
                function ($runner)
                {
                    return factories\transformer::build(
                        step::defaultFactory,
                        $runner
                    );
                },
            ))
            ->addStep(step::STEP_FILTER, array(
                function ($runner)
                {
                    return factories\filter::build(
                        step::defaultFactory,
                        $runner
                    );
                },
            ))
            ->addStep(step::STEP_PARSE, array(
                function ($runner)
                {
                    return factories\parser::build(
                        step::defaultFactory,
                        $runner
                    );
                },
            ))
            ->addStep(step::STEP_GENERATE, array(
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
                },
            ))
            ->addStep(step::STEP_EXECUTE, array(
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
                },
            ))
            ->addStep(step::STEP_TRANSFORM, array(
                function ($runner)
                {
                    return factories\transformer::build(
                        'svn',
                        $runner
                    );
                },
            ))
        ;
    }

}
