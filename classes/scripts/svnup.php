<?php

namespace autodeploy\scripts;

use autodeploy;
use autodeploy\step;
use autodeploy\factories;
use autodeploy\php\arguments\parser;

final class svnup extends autodeploy\script
{

    public function init(array $args = array())
    {
        $this->getRunner()->getProfile()
            ->setName('svn')
            ->setParsers(array(
                step::defaultFactory,
            ))
        ;

        parent::init($args);
    }

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
                    $runner->getProfile()
                        ->setName('ezpublish')
                        ->setOrigin('svn')
                        ->setParsers(array(
                            'ini',
                            'override',
                            'template',
                            'template_autoload',
                            'module',
                            'translation',
                            'design_base',
                            'active_extensions',
                            'autoload',
                        ));

                    if (substr( php_uname(), 0, 7 ) == "Windows")
                    {
                        $output = "A    extension/labackoffice/settings/site.ini.append.php\n";
                        $output .= "A    design/deco/templates/page_mainarea.tpl\n";
                        $output .= "A    extension/labackoffice/settings/override.ini.append.php\n";
                        $output .= "A    bin/toto.php\n";
                        $output .= "U    extension/labackoffice/classes/toto.php\n";
                        $output .= "U    extension/labackoffice/settings/design.ini.append.php";

                        $iterator = $runner->getIterator()->end()->getChildren();

                        foreach (explode("\n", $output) as $s)
                        {
                            $iterator->append($s);
                        }
                    }

                    return factories\transformer::build(
                        $runner->getProfile()->getOrigin(),
                        $runner
                    );
                },
            ))
            ->addStep(step::STEP_FILTER, array(
                function ($runner)
                {
                    return factories\filter::build(
                        $runner->getProfile()->getOrigin(),
                        $runner
                    );
                },
                function ($runner)
                {
                    return factories\profile\filter::build(
                        $runner->getProfile()->getName(),
                        $runner
                    );
                },
            ))
            ->addStep(step::STEP_PARSE, array(
                function ($runner, $parser)
                {
                    return factories\profile\parser::build(
                        array(
                            $runner->getProfile()->getName(),
                            $parser,
                            $runner->getProfile()->getOrigin(),
                        ),
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
                            $task['parser']
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
        ;
    }

}
