<?php

namespace autodeploy\definitions\php;

interface observable
{

    public function addObserver(observer $observer);

    public function callObservers($event);

}
