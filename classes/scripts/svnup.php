<?php

namespace autodeploy\scripts;

use autodeploy;

final class svnup extends autodeploy\script implements autodeploy\aggregators\runner
{

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

                $runner->getProfil()->setOrigin(current($origin));
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

        $this->addArgumentHandler(
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
        );

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
