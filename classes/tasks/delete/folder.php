<?php

namespace autodeploy\tasks\delete;

use autodeploy;

class folder extends autodeploy\task
{

    const TYPE = 'delete_folder';

    public function __toString()
    {
        $sWildcard = $this->getRunner()->getSystem()->cleanPath( implode(' ', $this->wildcards) );

        switch ($this->getRunner()->getSystem()->getOsType())
        {
            case 'win32':
            {
                return "del /Q /S $sWildcard";
                break;
            }
            case 'unix':
            {
                return "rm -rfv $sWildcard";
                break;
            }
        }
    }

}
