<?php

namespace autodeploy\php;

class locale
{

    protected $value = null;

    /**
     * @param null $value
     */
    public function __construct($value = null)
    {
        if ($value !== null)
        {
            $this->value = (string) $value;
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }

    /**
     * @param $string
     * @return
     */
    public function _($string)
    {
        return $string;
    }

    /**
     * @param $singular
     * @param $plural
     * @param $quantity
     * @return
     */
    public function __($singular, $plural, $quantity)
    {
        return ($quantity <= 1 ? $singular : $plural);
    }

}
