<?php

namespace autodeploy\parsers;

use autodeploy;

class none extends autodeploy\parser
{

    protected static $singleton = null;

    const PATTERN = '([^.]+)';

    /**
     * @param \autodeploy\element $element
     * @param array|null $matches
     * @param null $i
     * @return bool
     */
    public function hasMatches(autodeploy\element $element, array & $matches = null, & $i = null)
    {
        return preg_match('@'.self::PATTERN.'@', $element->file, $matches) && !is_null($i = 1);
    }

    /**
     * @abstract
     * @return void
     */
    public function getTaskType()
    {
        return autodeploy\tasks\execute\script::TYPE;
    }

}
