<?php

namespace autodeploy\php\sapi\cli;

class prefix
{

    protected $value = '';
    protected $styler = null;

    /**
     * @param string $value
     * @param styler|null $styler
     */
    public function __construct($value = '', styler $styler = null)
    {
        $this
            ->setValue($value)
            ->setStyler($styler?: new styler())
        ;
    }

    /**
     * @param $value
     * @return prefix
     */
    public function setValue($value)
    {
        $this->value = (string) $value;

        return $this;
    }

    /**
     * @param styler $styler
     * @return prefix
     */
    public function setStyler(styler $styler)
    {
        $this->styler = $styler;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->styler->colorize($this->value);
    }

}
