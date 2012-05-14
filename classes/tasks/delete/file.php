<?php

namespace autodeploy\tasks\delete;

use autodeploy;
use autodeploy\php;

class file extends autodeploy\task
{

    const TYPE = 'delete_file';

    public function __toString()
    {
        $sWildcard = $this->getWildcardsAsString();

        switch ($this->getRunner()->getSystem()->getOsType())
        {
            case php\system::OSTYPE_WIN:
            {
                return "del /S /Q $sWildcard";
                break;
            }
            case php\system::OSTYPE_UNIX:
            case php\system::OSTYPE_MAC:
            {
                return "rm -vf $sWildcard";
                break;
            }
        }
    }

}
