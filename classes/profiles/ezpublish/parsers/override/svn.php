<?php

namespace autodeploy\profiles\ezpublish\parsers\override;

use
    autodeploy,
    autodeploy\profiles\ezpublish\parsers
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
            || autodeploy\profiles\svn\tools::isAddedOrDeleted($element)&& $this->getOtherInstance('template')->hasMatches($element, $matches, $i);
    }

}
