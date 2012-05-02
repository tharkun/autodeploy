<?php

namespace autodeploy\frameworks\ezpublish\parsers\module;

use
    autodeploy,
    autodeploy\frameworks\ezpublish\parsers
;

class svn extends parsers\module
{

    /**
     * @param \autodeploy\element $element
     * @param array|null $matches
     * @param null $i
     * @return bool
     */
    public function hasMatches(autodeploy\element $element, array & $matches = null, & $i = null)
    {
        return parent::hasMatches($element, $matches, $i)
            && $element->isAddedOrDeleted();
    }

}
