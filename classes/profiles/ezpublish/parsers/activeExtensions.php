<?php

namespace autodeploy\frameworks\ezpublish\parsers;

use
    autodeploy
;

class activeExtensions extends autodeploy\parser
{

    protected static $singleton = null;


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    const PATTERN = '(/extension.xml)?';


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    public function hasMatches(autodeploy\element $element, array & $matches = null, & $i = null)
    {
        return preg_match('@extension/[^/]+$@', $element->file, $matches) && !is_null($i = 0)
            || preg_match('@extension/[^/]+'.self::PATTERN.'$@', $element->file, $matches) && !is_null($i = 1)
            || preg_match('@settings/override/site.ini.append.php$@', $element->file, $matches) && !is_null($i = 0)
        ;
    }

    public function getTaskType()
    {
        return autodeploy\tasks\delete\file::TYPE;
    }

}
