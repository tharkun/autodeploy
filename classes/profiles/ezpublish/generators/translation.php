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
                \eZDir::path(array(
                    \eZSys::cacheDirectory(),
                    'translation',
                    '*',
                    $this->wildcard
                )),
            ),
            new autodeploy\php\options(array(
                'type'      => autodeploy\tasks\execute\script::TYPE,
                'command'   => "php",
                'wildcard'  => 'bin/php/ezgeneratetranslationcache.php' . ( $this->wildcard ? ' --ts-list="'.$this->wildcard.'"' : '' ),
                'grouped'   => false,
            )),
        ));
    }

}
