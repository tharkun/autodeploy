<?php

namespace autodeploy\writers;

use
    autodeploy,
    autodeploy\definitions\writers,
    autodeploy\reports
;

abstract class std extends autodeploy\writer implements writers\synchronous, writers\asynchronous
{

    protected $resource = null;

    /**
     *
     */
    public function __destruct()
    {
        if ($this->resource !== null)
        {
            $this->adapter->fclose($this->resource);
        }
    }

    /**
     * @param $value
     * @return \autodeploy\writer|std|mixed
     */
    public function write($value)
    {
        $this->getResource()->adapter->fwrite($this->resource, $value);

        return $this;
    }

    /**
     * @return \autodeploy\writer|std|mixed
     */
    public function clear()
    {
        return $this->write("\r");
    }

    /**
     * @param \autodeploy\reports\synchronous $report
     * @return \autodeploy\writer|std|mixed
     */
    public function writeSynchronous(reports\synchronous $report)
    {
        return $this->write((string) $report);
    }

    /**
     * @param \autodeploy\reports\asynchronous $report
     * @return \autodeploy\writer|std|mixed
     */
    public function writeAsynchronous(reports\asynchronous $report)
    {
        return $this->write((string) $report);
    }

    protected abstract function getResource();

}
