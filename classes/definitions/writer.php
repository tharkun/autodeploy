<?php

namespace autodeploy\definitions;

interface writer
{

    public function write($value);

    public function clear();

}
