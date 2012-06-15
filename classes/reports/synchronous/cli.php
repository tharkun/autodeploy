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
            ->addField( new fields\runner\commands\cli($firstLevelPrefix, $firstLevelStyler) )
            ->addField( new fields\runner\duration\cli($firstLevelPrefix, $firstLevelStyler) )
            ->addField( new fields\runner\memory\cli($firstLevelPrefix, $firstLevelStyler) )

            ->addField( new fields\step\title\cli($titlePrefix, $titleStyler) )
            ->addField( new fields\step\duration\cli($secondLevelPrefix) )
            ->addField( new fields\step\memory\cli($secondLevelPrefix) )
            ->addField( new fields\step\result\cli() )

            ->addField( new fields\step\files\cli() )
            ->addField( new fields\step\actions\cli() )
            ->addField( new fields\step\wildcards\cli() )
            ->addField( new fields\step\output\cli() )

            ->addField( new fields\task\client\cli() )
            ->addField( new fields\task\command\cli() )

            ->addField( new fields\command\stdout\cli() )
            ->addField( new fields\command\stderr\cli() )

        ;
    }

}
