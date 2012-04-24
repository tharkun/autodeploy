<?php

namespace autodeploy\writers\std;

use autodeploy\writers;

class out extends writers\std
{

    protected function getResource()
    {
        if ($this->resource === null)
        {
            $resource = $this->adapter->fopen('php://stdout', 'w');

            if ($resource === false)
            {
                throw new \RuntimeException('Unable to open php://stout stream');
            }

            $this->resource = $resource;
        }

        return $this;
    }

}
