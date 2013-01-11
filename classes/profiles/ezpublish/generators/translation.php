<?php

namespace autodeploy\profiles\ezpublish\generators;

use
    autodeploy,
    autodeploy\php\iterator
;

class translation extends autodeploy\generator
{

    /**
     * @return \autodeploy\php\iterator|void
     */
    public function generate()
    {
        $command = new autodeploy\commands\delete\folder( $this->getRunner() );
        $command->addWildcard( $command->cleanPath( \eZSys::cacheDirectory() . DIRECTORY_SEPARATOR . 'translation' . DIRECTORY_SEPARATOR . '*' . DIRECTORY_SEPARATOR . $this->wildcard ) );

        $command1 = new autodeploy\profiles\ezpublish\commands\ezgeneratetranslationcache( $this->getRunner() );
        $command1->addWildcard( $this->wildcard );

        return new iterator(array(
            $command,
            $command1,
        ));
    }

}
