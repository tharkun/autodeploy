<?php

namespace autodeploy\reports;

use autodeploy;

class synchronous extends autodeploy\report
{

    public function handleEvent($event, autodeploy\definitions\php\observable $observable)
    {
        return parent::handleEvent($event, $observable)->write($event);
    }

    public function addWriter(autodeploy\definitions\writers\synchronous $writer)
    {
        return $this->doAddWriter($writer);
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
