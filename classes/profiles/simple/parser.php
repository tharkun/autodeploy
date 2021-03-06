<?php

namespace autodeploy\profiles\simple;

use autodeploy;

class parser extends autodeploy\parser
{

    protected static $singleton = null;

    const PATTERN = '(.+)';

    /**
     * @param \autodeploy\element $element
     * @param array|null $matches
     * @param null $i
     * @return bool
     */
    public function hasMatches(autodeploy\element $element, array & $matches = null, & $i = null)
    {
        return preg_match('@'.self::PATTERN.'@', $element->name, $matches) && !is_null($i = 1);
    }

}
