<?php

namespace autodeploy\report\fields\command\stderr;

use
    autodeploy\report\fields,
    autodeploy\php\locale,
    autodeploy\outputs\cli\prefix,
    autodeploy\outputs\cli\styler,
    autodeploy\outputs\cli\table
;

class cli extends fields\command\stderr
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
                $this->value === null || $this->value === ''
                ?
                ''
                :
                $this->styler->colorize( $this->locale->_("ERR : --" . $this->value) )
            )
        ;
    }
}
