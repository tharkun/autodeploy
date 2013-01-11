<?php

namespace autodeploy\profiles\ezpublish\parsers;

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


    /**
     * @param \autodeploy\element $element
     * @param array $matches
     * @param null $i
     * @return bool|void
     */
    public function hasMatches(autodeploy\element $element, array & $matches = null, & $i = null)
    {
        return preg_match('@extension/[^/]+$@', $element->name, $matches) && !is_null($i = 0)
            || preg_match('@extension/[^/]+'.self::PATTERN.'$@', $element->name, $matches) && !is_null($i = 1)
            || preg_match('@settings/override/site.ini.append.php$@', $element->name, $matches) && !is_null($i = 0)
        ;
    }

}
