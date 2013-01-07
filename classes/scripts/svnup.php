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

        /*$o = new autodeploy\commands\php($runner);
        echo get_class($o), "\t", $o, "\n";

        $o = new autodeploy\commands\ezpgenerateautoloads($runner);
        echo get_class($o), "\t", $o, "\n";

        $o = new autodeploy\commands\ezgeneratetranslationcache($runner);
        $o->addWildcard('fre-FR');
        echo get_class($o), "\t", $o, "\n";

        $o2 = new autodeploy\commands\ezgeneratetranslationcache($runner);
        $o2->addWildcard('rus-RU');
        echo get_class($o2), "\t", $o2, "\n";

        $o->aggregate($o2);
        echo get_class($o), "\t", $o, "\n";

        $o3 = new autodeploy\commands\ezpgenerateautoloads($runner);
        //echo get_class($o3), "\t", $o->isAggregatableWith($o3), "\n";
        echo get_class($o2), "\t", $o->isAggregatableWith($o2), "\n";


        $o = new autodeploy\commands\delete\file($runner);
        $o->addWildcard('toto');
        echo get_class($o), "\t", $o, "\n";

        $o1 = new autodeploy\commands\delete\file($runner);
        $o1->addWildcard('titi');
        echo get_class($o1), "\t", $o1, "\n";

        //echo get_class($o3), "\t", $o->isAggregatableWith($o3), "\n";
        echo get_class($o1), "\t", $o->isAggregatableWith($o1), "\n";
        $o->aggregate($o1);
        echo get_class($o), "\t", $o, "\n";


        $o1 = new autodeploy\commands\delete\folder($runner);
        $o1->addWildcard('bin');
        echo get_class($o1), "\t", $o1, "\n";

        //exit;*/

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
                    return new autodeploy\task($runner, '', array($action['command']));
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
                        $output .= "A    extension/labackoffice/design/admin2/templates/log_viewer/read.tpl\n";
                        $output .= "A    extension/labackoffice/settings/override.ini.append.php\n";
                        $output .= "A    bin/toto.php\n";
                        $output .= "U    extension/labackoffice/classes/toto.php\n";
                        $output .= "U    settings/override/site.ini.append.php\n";
                        $output .= "A    extension/labackoffice/modules\n";
                        $output .= "U    extension/labackoffice/autoloads/eztemplateautoload.php\n";
                        $output .= "U    extension/labackoffice/translations/fre-FR/translation.ts\n";
                        $output .= "A    extension/labackoffice/design\n";
                        $output .= "U    extension/labackoffice/translations/rus-RU/translation.ts\n";
                        $output .= "U    extension/labackoffice/settings/design.ini.append.php\n";
                        $output .= "A    extension/labackoffice/classes/toto.php\n";

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
                    return new autodeploy\task($runner, '', array($action['command']));
                },
            ))
        ;

        return $this;
    }

}
