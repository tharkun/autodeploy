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
    }

}
