<?php

namespace autodeploy\definitions\php;

interface observer
{

    /**
     * @param $event
     * @param observable $observable
     * @return mixed
     */
    public function handleEvent($event, observable $observable);

}
