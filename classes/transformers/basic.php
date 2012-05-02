<?php

namespace autodeploy\transformers;

use autodeploy;

class none extends autodeploy\transformer
{

    /**
     * @param $line
     * @return none
     */
    public function transform($line)
    {
        if (is_null($line))
        {
            return $this;
        }
        $this->append(array(
            'file'      => $line,
        ));

        return $this;
    }

}
