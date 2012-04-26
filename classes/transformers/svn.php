<?php

namespace autodeploy\transformers;

use autodeploy;

class svn extends autodeploy\transformer
{

    /**
     * @param $line
     * @return svn
     */
    public function transform($line)
    {
        if (is_null($line))
        {
            return $this;
        }
        preg_match('@^([ADUCGE ]{1})([U ]{1})([B ]{1})  ([^ ]+)$@', $line, $aMatches);
        if (is_array($aMatches) && count($aMatches))
        {
            if ('' == trim($aMatches[1]))
            {
                $this->append(array(
                    'file'      => $aMatches[4],
                    'action'    => $aMatches[2]
                ));
            }
            else
            {
                $this->append(array(
                    'file'      => $aMatches[4],
                    'action'    => $aMatches[1]
                ));
            }
        }
        else
        {
            $this->append(array(
                'file'      => $line,
                'action'    => '',
            ));
        }

        return $this;
    }

}