<?php

namespace autodeploy\definitions;

interface step
{

    public function getName();

    public function runStep();

}
