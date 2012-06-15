<?php

namespace autodeploy\profiles\ezpublish\parsers;

use
    autodeploy
;

class ini extends autodeploy\parser
{

    protected static $singleton = null;


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    const PATTERN = '([^/]+).ini(.dev|.pp|.test)?(.bo)?(.append)?(.php)?';


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    public function hasMatches(autodeploy\element $element, array & $matches = null, & $i = null)
    {
        return preg_match('@(extension/[^/]+/)?settings/'.self::PATTERN.'$@', $element->name, $matches) && !is_null($i = 2)
            || preg_match('@(extension/[^/]+/)?settings/override/'.self::PATTERN.'$@', $element->name, $matches) && !is_null($i = 2)
            || preg_match('@(extension/[^/]+/)?settings/siteaccess/[^/]+/'.self::PATTERN.'$@', $element->name, $matches) && !is_null($i = 2)
        ;
    }

    public function getTaskType()
    {
        return autodeploy\tasks\delete\file::TYPE;
    }

}
