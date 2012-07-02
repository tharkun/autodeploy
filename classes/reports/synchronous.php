<?php

namespace autodeploy\reports;

use autodeploy;

class synchronous extends autodeploy\report
{

    public function handleEvent($event, autodeploy\definitions\php\observable $observable)
    {
        return parent::handleEvent($event, $observable)->write($event);
    }

    protected function write($event)
    {
        foreach ($this->writers as $writer)
        {
            $writer->writeSynchronous($this, $event);
        }

        return $this;
    }

}
