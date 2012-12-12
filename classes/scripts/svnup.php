<?php

namespace autodeploy\scripts;

use autodeploy;
use autodeploy\step;
use autodeploy\factories;
use autodeploy\php\arguments\parser;

final class svnup extends autodeploy\script
{

    private $customProfiles = array();

    /**
     * @param array $customProfiles
     * @return svnup
     */
    public function setCustomProfiles(array $customProfiles)
    {
        $this->customProfiles = $customProfiles;

        return $this;
    }

    /**
     * @return null
     */
    public function getCustomProfiles()
    {
        return $this->customProfiles;
    }

    /**
     * @return svnup
     */
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
            'Files to update'
        );

        return $this;
    }

    /**
     * @return svnup
     */
    protected function setStepHandlers()
    {
        $self = $this;

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
            /*->addStep(step::STEP_FILTER, array(
                function ($runner)
                {
                    return factories\profile\filter::instance()->with($runner)->make();
                },
            ))//*/
            ->addStep(step::STEP_PARSE, array(
                function ($runner)
                {
                    return factories\profile\parser::instance()->with($runner)->make();
                },
            ))
            ->addStep(step::STEP_INVOKE, array(
                function ($runner)
                {
                    $runner->addProfile('svn', true);
                },
            ))
            ->addStep(step::STEP_GENERATE, array(
                function ($runner, $task)
                {
                    return factories\profile\generator::instance($runner->getProfiles()->current()->getName(), 'up')->with($runner, $task['value'])->make();
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
            ->addStep(step::STEP_INVOKE, array(
                function ($runner) use ($self)
                {
                    $runner->addProfile('ezpublish', true);
                    foreach ($self->getCustomProfiles() as $name)
                    {
                        $runner->addProfile($name);
                    }
                    foreach ($runner->getProfiles() as $profile)
                    {
                        $profile->setOrigin('svn');
                    }
                },
            ))
            ->addStep(step::STEP_TRANSFORM, array(
                function ($runner)
                {
                    if (substr( php_uname(), 0, 7 ) == "Windows")
                    {
                        $output = "A    extension/labackoffice/settings/site.ini.append.php\n";
                        $output .= "A    design/deco/templates/page_mainarea.tpl\n";
                        $output .= "A    design/deco/templates/toto.tpl\n";
                        $output .= "A    extension/labackoffice/settings/override.ini.append.php\n";
                        $output .= "A    bin/toto.php\n";
                        $output .= "U    extension/labackoffice/classes/toto.php\n";
                        $output .= "U    extension/labackoffice/translations/fre-FR/translation.ts\n";
                        $output .= "U    extension/labackoffice/translations/rus-RU/translation.ts\n";
                        $output .= "U    extension/labackoffice/settings/design.ini.append.php";

                        $iterator = $runner->getIterator()->end()->getChildren();

                        foreach (explode("\n", $output) as $s)
                        {
                            $iterator->append($s);
                        }
                    }

                    return factories\profile\transformer::instance($runner->getProfiles()->current()->getOrigin())->with($runner)->make();
                },
            ))
            ->addStep(step::STEP_FILTER, array(
                function ($runner)
                {
                    return factories\profile\filter::instance($runner->getProfiles()->current()->getOrigin())->with($runner)->make();
                },
                function ($runner)
                {
                    return factories\profile\filter::instance($runner->getProfiles()->current()->getName())->with($runner)->make();
                },
            ))
            ->addStep(step::STEP_PARSE, array(
                function ($runner, $parser)
                {
                    return factories\profile\parser::instance(
                        $runner->getProfiles()->current()->getName(),
                        $parser,
                        $runner->getProfiles()->current()->getOrigin()
                    )->with($runner)->make();
                },
            ))
            ->addStep(step::STEP_GENERATE, array(
                function ($runner, $task)
                {
                    return factories\profile\generator::instance(
                        $runner->getProfiles()->current()->getName(),
                        $task['parser']
                    )->with($runner, $task['value'])->make();
                },
            ))
            ->addStep(step::STEP_EXECUTE, array(
                function ($runner, $action)
                {
                    return factories\task::instance(
                        str_replace('_', '\\', $action['type']),
                        $action['parser']
                    )
                        ->with($runner, $action['command'], $action['wildcard'])->make();
                },
            ))
        ;

        return $this;
    }

}
