<?php

namespace autodeploy\reports\synchronous;

use
    autodeploy\reports,
    autodeploy\report\fields,
    autodeploy\outputs\cli\prefix,
    autodeploy\outputs\cli\styler
;

class cli extends reports\synchronous
{

    public function __construct()
    {
        parent::__construct();

        $titlePrefix = new prefix("\t");
        $titleStyler = new styler(array('bold', 'underlined'), 'yellow');

        $firstLevelPrefix = new prefix('> ');
        $firstLevelStyler = new styler(array(), 'cyan');

        $secondLevelPrefix = new prefix('=> ', $firstLevelStyler);

        $this
            ->addField( new fields\runner\duration\cli($firstLevelPrefix, $firstLevelStyler) )

            ->addField( new fields\step\title\cli($titlePrefix, $titleStyler) )
            ->addField( new fields\step\duration\cli($secondLevelPrefix) )
            ->addField( new fields\step\result\cli() )

            ->addField( new fields\step\files\cli() )
            ->addField( new fields\step\actions\cli() )
            ->addField( new fields\step\wildcards\cli() )
            ->addField( new fields\step\output\cli() )

        ;
    }

}