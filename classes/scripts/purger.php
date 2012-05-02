<?php

namespace autodeploy\scripts;

use autodeploy;
use autodeploy\factories;

final class purger extends autodeploy\script implements autodeploy\aggregators\runner
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
                            $runner->getProfile()->getOrigin(),
                            $runner
                        );
                    },
                ),
                'filter'    => array(
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
                'parse'     => array(
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
                'generate'  => array(
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
                'execute'   => array(
                    function ($runner, $action)
                    {
                        return factories\task::build(
                            array(
                                str_replace('_', '\\', $action['type']),
                                $action['parser']
                            ),
                            $runner,
                            $action['command'],
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
            function($script, $argument, $origin) use ($runner)
            {
                if (sizeof($origin) != 1)
                {
                    throw new \InvalidArgument(sprintf($script->getLocale()->_('Bad usage of %s, do php %s --help for more informations'), $argument, $script->getName()));
                }

                $runner->getProfile()->setOrigin(current($origin));
            },
            array('-o', '--origin'),
            null,
            $this->locale->_('Origin of the f param')
        );

        $this->addArgumentHandler(
            function($script, $argument, $files) use ($runner)
            {
                if (sizeof($files) != 1)
                {
                    throw new \InvalidArgument(sprintf($script->getLocale()->_('Bad usage of %s, do php %s --help for more informations'), $argument, $script->getName()));
                }

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

                $runner->setFilesIterator( $iterator );
            },
            array('-f', '--files'),
            null,
            $this->locale->_('Files')
        );

        return $this;
    }

}
