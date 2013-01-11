<?php

namespace autodeploy\definitions\php;

interface aggregatable
{

    /**
     * @param aggregatable $object
     * @return mixed
     */
    public function aggregate(aggregatable $object);

    /**
     * @param aggregatable $object
     * @return mixed
     */
    public function isAggregatableWith(aggregatable $object);

}
