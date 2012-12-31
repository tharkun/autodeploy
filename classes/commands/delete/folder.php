<?php

namespace autodeploy\commands\delete;

use autodeploy;
use autodeploy\definitions\php\aggregatable;
use autodeploy\php;

class folder extends file implements aggregatable
{

    protected $recursive = true;

}