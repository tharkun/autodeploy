<?php

$runner
    ->getProfile()
        ->setName('svn')
        ->setParsers(array(
            autodeploy\step::defaultFactory,
        ))
;
