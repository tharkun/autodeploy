<?php

namespace autodeploy\profiles\ezpublish\parsers;

use
    autodeploy
;

class override extends autodeploy\parser
{

    protected static $singleton = null;


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    const PATTERN = 'override.ini(.dev|.pp|.test)?(.bo)?(.append)?(.php)?';


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    public function hasMatches(autodeploy\element $element, array & $matches = null, & $i = null)
    {
        return preg_match('@(extension/[^/]+/)?settings/'.self::PATTERN.'$@', $element->name, $matches) && !is_null($i = 2)
            || preg_match('@(extension/[^/]+/)?settings/override/'.self::PATTERN.'$@', $element->name, $matches) && !is_null($i = 2)
            || preg_match('@(extension/[^/]+/)?settings/siteaccess/[^/]+/'.self::PATTERN.'$@', $element->name, $matches) && !is_null($i = 2)
        ;
            //toto new tpl
    }

    public function getTaskType()
    {
        return autodeploy\tasks\delete\folder::TYPE;
    }

}
