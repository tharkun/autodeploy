<?php

namespace autodeploy\profiles\simple;

use autodeploy;

class transformer extends autodeploy\transformer
{

    /**
     * @param $line
     * @return simple
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
