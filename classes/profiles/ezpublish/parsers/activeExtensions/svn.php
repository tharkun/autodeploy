<?php

namespace autodeploy\profiles\ezpublish\parsers\activeExtensions;

use
    autodeploy,
    autodeploy\profiles\ezpublish\parsers
;

class svn extends parsers\activeExtensions
{

    /**
     * @param \autodeploy\element $element
     * @param array|null $matches
     * @param null $i
     * @return bool
     */
    public function hasMatches(autodeploy\element $element, array & $matches = null, & $i = null)
    {
        return preg_match('@extension/[^/]+$@', $element->file, $matches) && $element->isAddedOrDeleted() && !is_null($i = 0)
            || preg_match('@extension/[^/]+'.self::PATTERN.'$@', $element->file, $matches) && $element->isAddedOrDeleted() && !is_null($i = 1)
            || preg_match('@settings/override/site.ini.append.php$@', $element->file, $matches) && !is_null($i = 0)
        ;
    }

}
