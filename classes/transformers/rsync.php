<?php

namespace autodeploy\transformers;

use autodeploy;

class rsync extends autodeploy\transformer
{

    /**
     * @param $line
     * @return rsync
     */
    public function transform($line)
    {
        if (is_null($line))
        {
            return $this;
        }
        $this->append(array(
            'file'      => $line,
            'action'    => 'U',
        ));

        return $this;
    }

}
