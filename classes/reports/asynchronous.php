<?php

namespace autodeploy\reports;

use autodeploy;

abstract class asynchronous extends autodeploy\report
{
    protected $string = '';

    public function handleEvent($event, autodeploy\definitions\php\observable $observable)
    {
        parent::handleEvent($event, $observable)->build();

        if ($event === autodeploy\runner::runStop)
        {
            foreach ($this->writers as $writer)
            {
                $writer->writeAsynchronous($this);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->string;
    }

    protected function build()
    {
        $this->string .= parent::__toString();

        return $this;
    }
}
