<?php

namespace autodeploy\transformers;

use autodeploy;

class basic extends autodeploy\transformer
{

    /**
     * @param $line
     * @return basic
     */
    public function transform($line)
    {
        if (is_null($line))
        {
            return $this;
        }
        $this->append(array(
            'name'      => $line,
        ));

        return $this;
    }

}
