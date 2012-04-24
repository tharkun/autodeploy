<?php

namespace autodeploy\frameworks\ezpublish;

use
    autodeploy
;

class filter extends autodeploy\filter
{

    const PATTERN_EXCLUDED_FOLDERS = '(autoload|bin|cronjobs|schemas|support|update|tests)';

    /**
     * @param \autodeploy\iterator $iterator
     * @return ezpublish
     */
    public function filter(autodeploy\iterator $iterator)
    {
        foreach ($iterator as $element)
        {
            if ("" == $element->file
                || preg_match("@^".self::PATTERN_EXCLUDED_FOLDERS."@", $element->file)
            ) {
                $iterator->skip();
            }
        }

        return $this;
    }

}
