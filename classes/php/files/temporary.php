<?php

namespace autodeploy\php\files;

use autodeploy\php;

class temporary
{

    const PREFIX = 'FOO';

    protected $name = null;

    /**
     * @param null $prefix
     */
    public function __construct($prefix = null)
    {
        $this->name = tempnam(sys_get_temp_dir(), $prefix ?: self::PREFIX);

        $name = $this->name;

        register_shutdown_function(function() use ($name) {
            if (file_exists($name))
            {
                unlink($name);
            }
        });
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

}
