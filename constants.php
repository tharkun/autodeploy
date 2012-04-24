<?php

namespace autodeploy;

if ( version_compare( PHP_VERSION, '5.3' ) < 0 )
{
    print( "<h1>Unsupported PHP version " . PHP_VERSION . "</h1>" );
    print( "<p>This script does not run with PHP version lower than 5.3.</p>" );
    exit;
}

