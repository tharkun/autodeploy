<?php

namespace autodeploy\definitions\php;

interface observer
{

    public function handleEvent($event, observable $observable);

}
