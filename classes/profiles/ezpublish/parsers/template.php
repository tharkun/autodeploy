<?php

namespace autodeploy\profiles\ezpublish\parsers;

use
    autodeploy
;

class template extends autodeploy\parser
{

    protected static $singleton = null;


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    const PATTERN = '([^/]+).tpl';


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    public function hasMatches(autodeploy\element $element, array & $matches = null, & $i = null)
    {
        return preg_match('@(extension/)?design/[^/]+/(override/)?templates/(.*/)?'.self::PATTERN.'$@', $element->name, $matches) && !is_null($i = count($matches) - 1);
    }

    /*public function getTaskType()
    {
        return autodeploy\tasks\delete\file::TYPE;
    }//*/

}
