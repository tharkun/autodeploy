<?php

namespace autodeploy\profiles\ezpublish;

use
    autodeploy
;

class filter extends autodeploy\filter
{

    const PATTERN_EXCLUDED_FOLDERS = '(autoload|bin|cronjobs|schemas|support|update|tests)';

    /**
     * @param \autodeploy\php\iterator $iterator
     * @return ezpublish
     */
    public function filter(autodeploy\php\iterator $iterator)
    {
        foreach ($iterator as $element)
        {
            if ("" == $element->name
                || preg_match("@^".self::PATTERN_EXCLUDED_FOLDERS."@", $element->name)
            ) {
                $iterator->skip();
            }
        }

        return $this;
    }

}
