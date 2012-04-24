<?php

namespace autodeploy\frameworks\ezpublish\parsers\override;

use
    autodeploy,
    autodeploy\frameworks\ezpublish\parsers
;

class svn extends parsers\override
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
            || $element->isAddedOrDeleted()&& $this->getOtherInstance('template')->hasMatches($element, $matches, $i);
    }

}
