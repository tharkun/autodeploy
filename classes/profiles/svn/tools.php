<?php

namespace autodeploy\profiles\svn;

use
    autodeploy
;

abstract class tools
{

    /**
     * @param \autodeploy\element $element
     * @return bool
     */
    public static function isAdded(autodeploy\element $element)
    {
        return 'A' == $element->action;
    }

    /**
     * @param \autodeploy\element $element
     * @return bool
     */
    public static function isDeleted(autodeploy\element $element)
    {
        return 'D' == $element->action;
    }

    /**
     * @param \autodeploy\element $element
     * @return bool
     */
    public static function isUpdated(autodeploy\element $element)
    {
        return 'U' == $element->action;
    }

    /**
     * @param \autodeploy\element $element
     * @return bool
     */
    public static function isConflict(autodeploy\element $element)
    {
        return 'C' == $element->action;
    }

    /**
     * @param \autodeploy\element $element
     * @return bool
     */
    public static function isMerged(autodeploy\element $element)
    {
        return 'G' == $element->action;
    }

    /**
     * @param \autodeploy\element $element
     * @return bool
     */
    public static function isExisted(autodeploy\element $element)
    {
        return 'E' == $element->action;
    }

    /**
     * @param \autodeploy\element $element
     * @return bool
     */
    public static function isAddedOrDeleted(autodeploy\element $element)
    {
        return self::isAdded($element) || self::isDeleted($element);
    }

}
