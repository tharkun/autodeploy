<?php

namespace autodeploy\writers;

use
    autodeploy,
    autodeploy\definitions\writers,
    autodeploy\reports
;

abstract class std extends autodeploy\writer implements writers\synchronous//, writers\asynchronous
{

    protected $resource = null;

    public function __destruct()
    {
        if ($this->resource !== null)
        {
            $this->adapter->fclose($this->resource);
        }
    }

    public function write($value)
    {
        $this->getResource()->adapter->fwrite($this->resource, $value);

        return $this;
    }

    public function clear()
    {
        return $this->write("\r");
    }

    public function writeSynchronous(reports\synchronous $report)
    {
        return $this->write((string) $report);
    }

    /*public function writeAsynchronousReport(reports\asynchronous $report)
    {
        return $this->write((string) $report);
    }*/

    protected abstract function getResource();

}
