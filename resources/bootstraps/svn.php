<?php

$runner
    ->getProfil()
        ->setName('svn')
        ->setParsers(array(
            autodeploy\step::defaultFactory,
        ))
;
