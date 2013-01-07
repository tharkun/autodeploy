<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy,
    autodeploy\php\iterator
;

class translation extends autodeploy\generator
{

    public function generate()
    {
        $command = new autodeploy\commands\delete\folder( $this->getRunner() );
        $command->addWildcard(\eZDir::path(array(
            \eZSys::cacheDirectory(),
            'translation',
            '*',
            $this->wildcard
        )));

        $command1 = new autodeploy\commands\ezgeneratetranslationcache( $this->getRunner() );
        $command1->addWildcard($this->wildcard);

        return new iterator(array(
            new autodeploy\php\options(array(
                'todo' => $command
            )),
            new autodeploy\php\options(array(
                'todo'   => $command1,
            )),
        ));
        /*return new iterator(array(
            new autodeploy\php\options(array(
                'type'      => autodeploy\tasks\delete\folder::TYPE,
                \eZDir::path(array(
                    \eZSys::cacheDirectory(),
                    'translation',
                    '*',
                    $this->wildcard
                )),
            )),
            new autodeploy\php\options(array(
                'type'      => autodeploy\tasks\execute\script::TYPE,
                'command'   => "php",
                'wildcard'  => 'bin/php/ezgeneratetranslationcache.php' . ( $this->wildcard ? ' --ts-list="'.$this->wildcard.'"' : '' ),
                'grouped'   => false,
            )),
        ));//*/
    }

}
