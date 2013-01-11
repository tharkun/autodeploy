<?php

namespace autodeploy\profiles\ezpublish\parsers;

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


    /**
     * @param \autodeploy\element $element
     * @param array $matches
     * @param null $i
     * @return bool|void
     */
    public function hasMatches(autodeploy\element $element, array & $matches = null, & $i = null)
    {
        return preg_match('@extension/[^/]+/autoloads/'.self::PATTERN.'$@', $element->name, $matches) && !is_null($i = 1);
    }

}
