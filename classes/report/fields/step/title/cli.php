<?php

namespace autodeploy\report\fields\step\title;

use
    autodeploy\report\fields,
    autodeploy\php\locale,
    autodeploy\outputs\cli\prefix,
    autodeploy\outputs\cli\styler,
    autodeploy\outputs\cli\table
;

class cli extends fields\step\title
{

    protected $prefix = null;
    protected $styler = null;

    public function __construct(prefix $prefix = null, styler $styler = null, locale $locale = null)
    {
        parent::__construct($locale);

        $this
            ->setPrefix($prefix ?: new prefix())
            ->setStyler($styler ?: new styler())
        ;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function setStyler(styler $styler)
    {
        $this->styler = $styler;

        return $this;
    }

    public function __toString()
    {
        return $this->prefix .
            (
                $this->number === null || $this->title === null
                ?
                ''
                :
                $this->styler->colorize(sprintf($this->locale->_("Step %d : %s"), $this->number, $this->title))
            )
            . PHP_EOL
            . PHP_EOL
        ;
    }
}