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
        return new iterator(array(
            array(
                autodeploy\tasks\delete\folder::TYPE,
                \eZDir::path( array( \eZSys::cacheDirectory(), 'translation' ) ) . '/*',
            ),
            array(
                autodeploy\tasks\execute\script::TYPE,
                'php',
                'bin/php/ezgeneratetranslationcache.php'
            )
        ));
    }

}
