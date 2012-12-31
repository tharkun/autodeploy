<?php

namespace autodeploy\definitions\php;

interface aggregatable
{

    public function aggregate(aggregatable $object);

    public function isAggregatableWith(aggregatable $object);

}
