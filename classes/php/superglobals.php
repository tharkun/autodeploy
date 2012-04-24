<?php

namespace autodeploy\php;

class superglobals
{

    protected $properties = array();

    /**
     * @param $property
     * @param $value
     * @return void
     */
    public function __set($property, $value)
    {
        $this->check($property)->properties[$property] = $value;
    }

    /**
     * @param $property
     * @return array|string
     */
    public function & __get($property)
    {
        $this->check($property);

        if (array_key_exists($property, $this->properties) === true)
        {
            return $this->properties[$property];
        }

        switch ($property)
        {
            case 'GLOBALS':
            {
                return $GLOBALS;
            }
            case '_SERVER':
            {
                return $_SERVER;
            }
            case '_GET':
            {
                return $_GET;
            }
            case '_POST':
            {
                return $_POST;
            }
            case '_FILES':
            {
                return $_FILES;
            }
            case '_COOKIE':
            {
                return $_COOKIE;
            }
            case '_SESSION':
            {
                return $_SESSION;
            }
            case '_REQUEST':
            {
                return $_REQUEST;
            }
            case '_ENV':
            {
                return $_ENV;
            }
        }
    }

    /**
     * @throws \InvalidArgumentException
     * @param $name
     * @return superglobals
     */
    protected function check($name)
    {
        switch ($name)
        {
            case 'GLOBALS':
            case '_SERVER':
            case '_GET':
            case '_POST':
            case '_FILES':
            case '_COOKIE':
            case '_SESSION':
            case '_REQUEST':
            case '_ENV':
                break;

            default:
                throw new \InvalidArgumentException('PHP superglobal \'$' . $name . '\' does not exist');
        }

        return $this;
    }

}
