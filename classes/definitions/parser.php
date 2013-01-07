<?php

namespace autodeploy\definitions;

use autodeploy;

interface parser
{

    /**
     * @abstract
     * @param \autodeploy\element $element
     * @param array|null $matches
     * @param null $i
     * @return void
     */
    public function hasMatches(autodeploy\element $element, array & $matches = null, & $i = null);

}
