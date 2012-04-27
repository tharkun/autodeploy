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
                            $runner->getProfil()->getOrigin(),
                            $runner
                        );
                    },
                ),
                'filter'    => array(
                    function ($runner)
                    {
                        return factories\filter::build(
                            $runner->getProfil()->getOrigin(),
                            $runner
                        );
                    },
                    function ($runner)
                    {
                        return factories\framework\filter::build(
                            $runner->getProfil()->getName(),
                            $runner
                        );
                    },
                ),
                'parse'     => array(
                    function ($runner, $parser)
                    {
                        return factories\framework\parser::build(
                            array(
                                $runner->getProfil()->getName(),
                                $parser,
                                $runner->getProfil()->getOrigin()
                            ),
                            $runner
                        );
                    }
                ),
                'generate'  => array(
                    function ($runner, $task)
                    {
                        return factories\framework\generator::build(
                            array(
                                $runner->getProfil()->getName(),
                                $task['parser']
                           ),
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

        /*$this->addArgumentHandler(
            function($script, $argument, $values) use ($runner) {
                if (sizeof($values) != 1)
                {
                    throw new \InvalidArgument(sprintf($script->getLocale()->_('Bad usage of %s, do php %s --help for more informations'), $argument, $script->getName()));
                }

                $bootstrapFile = realpath($values[0]);

                if ($bootstrapFile === false || is_file($bootstrapFile) === false || is_readable($bootstrapFile) === false)
                {
                    throw new \InvalidArgument(sprintf($script->getLocale()->_('Bootstrap file \'%s\' does not exist'), $values[0]));
                }

                $runner->setBootstrapFile($bootstrapFile);
            },
            array('-bf', '--bootstrap-file'),
            '<file>',
            $this->locale->_('Include <file> before executing each test method')
        );//*/

        return $this;
    }

    /**
     * @throws \Exception
     * @param array $arguments
     * @return runner
     */
    public function run(array $arguments = array())
    {
        try
        {
            parent::run($arguments);

            $this->getRunner()->run();

        }
        catch (\Exception $exception)
        {
            throw $exception;
        }

        return $this;
    }

}
