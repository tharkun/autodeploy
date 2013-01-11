<?php

namespace autodeploy\definitions\php;

interface observable
{

    /**
     * @param observer $observer
     * @return mixed
     */
    public function addObserver(observer $observer);

    /**
     * @param $event
     * @return mixed
     */
    public function callObservers($event);

}
