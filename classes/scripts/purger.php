<?php

namespace autodeploy\scripts;

use autodeploy;
use autodeploy\step;
use autodeploy\factories;
use autodeploy\php\arguments;

final class purger extends autodeploy\script
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
                            $runner->getProfile()->getOrigin(),
                            $runner
                        );
                    },
                ),
                step::STEP_FILTER => array(
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
                ),
                step::STEP_PARSE     => array(
                    function ($runner, $parser)
                    {
                        return factories\profile\parser::build(
                            array(
                                $runner->getProfile()->getName(),
                                $parser,
                                $runner->getProfile()->getOrigin()
                            ),
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
                                $task['parser']
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
            function($script, $argument, $origin) use ($runner)
            {
                $runner->getProfile()->setOrigin(current($origin));
            },
            array('-o', '--origin'),
            arguments\parser::TYPE_SINGLE,
            null,
            'Origin of the f param'
        );

        $this->addArgumentHandler(
            function($script, $argument, $files) use ($runner)
            {
                $stdObject = json_decode( current($files) );

                if (substr( php_uname(), 0, 7 ) == "Windows" || '/var/www/dekio.fr'==getcwd())
                {
                    $s = "2";
                    $stdObject->$s = "A    extension/labackoffice/settings/site.ini.append.php";

                    $s = "3";
                    $stdObject->$s = "A    design/deco/templates/page_mainarea.tpl";
                    $s = "7";
                    $stdObject->$s = "A    extension/labackoffice/settings/override.ini.append.php";

                    $s = "4";
                    $stdObject->$s = "A    bin/toto.php";

                    $s = "5";
                    $stdObject->$s = "U    extension/labackoffice/classes/toto.php";

                    $s = "6";
                    $stdObject->$s = "U    extension/labackoffice/settings/design.ini.append.php";
                }

                $iterator = new autodeploy\iterator();
                foreach ($stdObject as $element)
                {
                    $iterator->append($element);
                }

                $runner->setInputIterator( $iterator );
            },
            array('-f', '--files'),
            arguments\parser::TYPE_SINGLE,
            null,
            'Files'
        );

        return $this;
    }

}
