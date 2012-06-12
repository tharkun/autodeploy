<?php
// Set a default time zone if none is given to avoid "It is not safe to rely
// on the system's timezone settings" warnings. The time zone can be overriden
// in config.php or php.ini.
if ( !ini_get( "date.timezone" ) )
{
    date_default_timezone_set( "UTC" );
}

require 'autoload.php';

$runner
    ->getProfile()
        ->setName('svn')
        ->setParsers(array(
            autodeploy\step::defaultFactory,
        ))
;
