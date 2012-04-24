<?php

namespace autodeploy;

final class system
{

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
            $this->osType = "win32";
            $this->fileSeparator = "\\";
        }
        else if ( substr( $uname, 0, 3 ) == "Mac" )
        {

            $this->osType = "max";
            $this->fileSeparator = "/";
        }
        else
        {
            $this->osType = "unix";
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
            case "win32":
            {
                $path = preg_replace("@/@", "\\", $path);
                break;
            }
            case 'mac':
            case 'unix':
            {

            }
        }

        return $path;
    }

}
