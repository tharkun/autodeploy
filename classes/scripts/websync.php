<?php

namespace autodeploy\scripts;

use autodeploy;
use autodeploy\step;
use autodeploy\factories;
use autodeploy\php\arguments\parser;

final class websync extends autodeploy\script
{

    protected function setArgumentHandlers()
    {
        $runner = $this->getRunner();

        $this->addArgumentHandler(
            function($script, $argument, $values) use ($runner)
            {
                foreach ($values as $value)
                {
                    $runner->getIterator()->getChildren()->append( $value );
                }
            },
            array(''),
            parser::TYPE_MULTIPLE,
            parser::MANDATORY,
            'file',
            'Files to rsync'
        );

        return $this;
    }

    protected function setStepHandlers()
    {
        $this->getRunner()
            ->addStep(step::STEP_INVOKE, array(
                function ($runner)
                {
                    $runner->addProfile('simple');
                },
            ))
            ->addStep(step::STEP_TRANSFORM, array(
                function ($runner)
                {
                    return factories\profile\transformer::instance()->with($runner)->make();
                },
            ))
            ->addStep(step::STEP_FILTER, array(
                function ($runner)
                {
                    return factories\profile\filter::instance()->with($runner)->make();
                },
            ))
            ->addStep(step::STEP_PARSE, array(
                function ($runner)
                {
                    return factories\profile\parser::instance()->with($runner)->make();
                },
            ))
            ->addStep(step::STEP_INVOKE, array(
                function ($runner)
                {
                    $runner->addProfile('websync', true);
                },
            ))
            ->addStep(step::STEP_GENERATE, array(
                function ($runner, $task)
                {
                    return factories\profile\generator::instance($runner->getProfiles()->current()->getName(), 'main')->with($runner, $task['value'])->make();
                },
            ))
            ->addStep(step::STEP_EXECUTE, array(
                function ($runner, $action)
                {
                    return factories\task::instance(str_replace('_', '\\', $action['type']), $action['parser'])
                        ->with($runner, $action['command'], $action['wildcard'])
                        ->make()
                    ;
                },
            ))
        ;
    }

}
