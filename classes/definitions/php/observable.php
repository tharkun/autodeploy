<?php

namespace autodeploy\definitions\php;

interface observable
{

    public function callObservers($event);

}
