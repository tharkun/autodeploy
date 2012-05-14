<?php

namespace autodeploy\php;

final class system extends struct
{

    const OSTYPE_WIN  = 'win32';
    const OSTYPE_MAC  = 'mac';
    const OSTYPE_UNIX = 'unix';

    protected $osType = null;
    protected $fileSeparator = null;

    /**
     *
     */
    public function __construct()
    {
        $uname = php_uname();
        if ( substr( $uname, 0, 7 ) == "Windows" )
        {
            $this->osType = self::OSTYPE_WIN;
            $this->fileSeparator = "\\";
        }
        else if ( substr( $uname, 0, 3 ) == "Mac" )
        {

            $this->osType = self::OSTYPE_MAC;
            $this->fileSeparator = "/";
        }
        else
        {
            $this->osType = self::OSTYPE_UNIX;
            $this->fileSeparator = "/";
        }
    }

    /**
     * @return null|string
     */
    public function getOsType()
    {
        return $this->osType;
    }

    /**
     * @return null|string
     */
    public function getFileSeparator()
    {
        return $this->fileSeparator;
    }

    /**
     * @param $path
     * @return mixed
     */
    public function cleanPath($path)
    {
        switch ($this->getOsType())
        {
            case self::OSTYPE_WIN:
            {
                $path = preg_replace("@/@", "\\", $path);
                break;
            }
        }

        return $path;
    }

}
