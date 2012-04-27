<?php

namespace autodeploy;

require_once __DIR__ . '/../classes/php/autoloader.php';

$purger = new scripts\svnup(__FILE__);
$purger->run();
