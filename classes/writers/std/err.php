<?php

namespace autodeploy\writers\std;

use autodeploy\writers;

class err extends writers\std
{

    /**
     * @return err
     * @throws \RuntimeException
     */
    protected function getResource()
    {
        if ($this->resource === null)
        {
            $resource = $this->adapter->fopen('php://stderr', 'w');

            if ($resource === false)
            {
                throw new \RuntimeException('Unable to open php://stderr stream');
            }

            $this->resource = $resource;
        }

        return $this;
    }

}
