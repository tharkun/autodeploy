<?php

namespace autodeploy\tasks\delete;

use autodeploy;

class file extends autodeploy\task
{

    const TYPE = 'delete_file';

    public function __toString()
    {
        $sWildcard = $this->getRunner()->getSystem()->cleanPath( implode(' ', $this->wildcards) );

        switch ($this->getRunner()->getSystem()->getOsType())
        {
            case 'win32':
            {
                return "del /S /Q $sWildcard";
                break;
            }
            case 'unix':
            {
                return "rm -vf $sWildcard";
                break;
            }
        }
    }

}
