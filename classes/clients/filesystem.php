<?php

namespace autodeploy\clients;

use autodeploy;

class filesystem extends autodeploy\client
{

    /**
     * @param \autodeploy\task $task
     * @return filesystem
     */
    final function execute(autodeploy\task $task)
    {
        $command = new autodeploy\command( $this->getCommand() );
        $command->execute($task->getClosureForStdout(), $task->getClosureForStderr());

        return $this;
    }

}
