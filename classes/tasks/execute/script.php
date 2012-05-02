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
                $aCommands[] = trim( $self->getRunner()->getSystem()->cleanPath( $sWildCard ) );
            }
            return implode(' ', array_unique($aCommands));
        };

        return $this->command . ' ' . $fMakeCommand(is_array($this->wildcards) ? $this->wildcards : array($this->wildcards));
    }

}
