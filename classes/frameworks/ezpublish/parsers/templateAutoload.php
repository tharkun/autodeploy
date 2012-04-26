<?php

namespace autodeploy\frameworks\ezpublish\parsers;

use
    autodeploy
;

class templateAutoload extends autodeploy\parser
{

    protected static $singleton = null;


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    const PATTERN = '(eztemplateautoload.php)';


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    public function hasMatches(autodeploy\element $element, array & $matches = null, & $i = null)
    {
        return preg_match('@extension/[^/]+/autoloads/'.self::PATTERN.'$@', $element->file, $matches) && !is_null($i = 1);
    }

    public function getTaskType()
    {
        return autodeploy\tasks\delete\file::TYPE;
    }

}