<?php

namespace autodeploy\report\fields\step\title;

use
    autodeploy\report\fields,
    autodeploy\php\locale,
    autodeploy\php\sapi\cli\prefix,
    autodeploy\php\sapi\cli\styler,
    autodeploy\php\sapi\cli\table
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
        return PHP_EOL
            . $this->prefix .
            (
                $this->currentStepNumber === null || $this->title === null
                ?
                ''
                :
                $this->styler->colorize(sprintf($this->locale->_("Step %d/%d : %s"), $this->currentStepNumber, $this->totalStepNumber, $this->title))
            )
            . PHP_EOL
            . PHP_EOL
        ;
    }
}
