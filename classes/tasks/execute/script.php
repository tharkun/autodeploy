<?php

namespace autodeploy\tasks\execute;

use autodeploy;

class script extends autodeploy\task
{

    const TYPE = 'execute_script';

    public function __toString()
    {
        $self = $this;

        $fMakeCommand = function (array $aWildCards) use ($self)
        {
            $aCommands = array();
            foreach ($aWildCards as $sWildCard)
            {
                $sWildCard = $self->getRunner()->getSystem()->cleanPath( $sWildCard );
                preg_match("@^[a-zA-Z0-9-_/\\\\.]+.(php)( .+)?$@", $sWildCard, $aMatches);

                $sBatchPrefix = '';
                /*if (count($aMatches))
                {
                    switch ($aMatches[1])
                    {
                        case 'php':
                            switch ($self->getRunner()->getSystem()->getOsType())
                            {
                                case 'win32':
                                case 'unix':
                                    //$sBatchPrefix = "php";
                                    break;
                            }
                            break;
                    }
                }
                if ('' == $sBatchPrefix)
                {
                    //throw new \RuntimeException('Unsupported script extension.');
                }*/
                $aCommands[] = trim("$sBatchPrefix $sWildCard");
            }
            return implode(' && ', array_unique($aCommands));
        };

        return $fMakeCommand(is_array($this->wildcards) ? $this->wildcards : array($this->wildcards));
    }

}
