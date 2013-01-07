<?php

namespace autodeploy\profiles\ezpublish\parsers;

use
    autodeploy
;

class autoload extends autodeploy\parser
{

    protected static $singleton = null;


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    const PATTERN = '(.+).php';


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    public function hasMatches(autodeploy\element $element, array & $matches = null, & $i = null)
    {
        return preg_match('@(extension/[^/]+/)?classes/'.self::PATTERN.'$@', $element->name, $matches) && !is_null($i = 2);
    }

}
