<?php

namespace autodeploy\filters;

use
    autodeploy
;

class svn extends autodeploy\filter
{

    /**
     * @param \autodeploy\iterator $iterator
     * @return svn
     */
    public function filter(autodeploy\iterator $iterator)
    {
        foreach ($iterator as $element)
        {
            if (preg_match("@^At revision@", $element->file)
                || preg_match("@^Updated to revision@", $element->file)
                || preg_match("@^Updated external to revision@", $element->file)
                || preg_match("@^External at revision@", $element->file)
                || preg_match("@^Fetching external item into@", $element->file)

                || $this->_shallWeFilter_restored($element)
                || $this->_shallWeFilter_locked($element)
                || $this->_shallWeFilter_conflict($element)
                || $this->_shallWeFilter_skipped($element)
            ) {
                $iterator->skip();
            }
        }

        return $this;
    }

    /**
     * @param \autodeploy\element $element
     * @return int
     */
    private function _shallWeFilter_restored(autodeploy\element $element)
    {
        return preg_match("@^Restored @", $element->file);
    }

    /**
     * @param \autodeploy\element $element
     * @return bool
     */
    private function _shallWeFilter_locked(autodeploy\element $element)
    {
        return preg_match("@^svn: warning:@", $element->file)
            || preg_match("@^svn: Unable to lock@", $element->file)
            || preg_match("@^svn: Working copy '[^']+' locked$@", $element->file)
            || preg_match("@^svn: run 'svn cleanup'@", $element->file);
        ;
    }

    /**
     * @param \autodeploy\element $element
     * @return bool
     */
    private function _shallWeFilter_conflict(autodeploy\element $element)
    {
        $aSelectOption = array(
            '(e)  edit',
            '(df) diff-full',
            '(r)  resolved',
            '(dc) display-conflict',
            '(mc) mine-conflict',
            '(tc) theirs-conflict',
            '(mf) mine-full',
            '(tf) theirs-full',
            '(p)  postpone',
            '(l)  launch',
            '(s)  show all',
        );
        return $element->isConflict()
            || preg_match("@^Conflict discovered in@", $element->file)
            || preg_match("@^(Select:)?\s+(".preg_replace(array("@\s+@", "@\(@", "@\)@"), array(" ", "\(", "\)") ,implode('|', $aSelectOption)).")@", $element->file)
            || preg_match("@^Summary of conflicts@", $element->file)
            || preg_match("@^\s*Text conflicts@", $element->file)
        ;
    }

    /**
     * @param \autodeploy\element $element
     * @return bool
     */
    private function _shallWeFilter_skipped(autodeploy\element $element)
    {
        return preg_match("@^\s*Skipped paths@", $element->file)
            || preg_match("@^Skipped '[^']+'$@", $element->file);
        ;
    }

}