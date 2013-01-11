<?php

namespace autodeploy\report\fields\command\stderr;

use
    autodeploy\report\fields,
    autodeploy\php\locale,
    autodeploy\php\sapi\cli\prefix,
    autodeploy\php\sapi\cli\styler,
    autodeploy\php\sapi\cli\table
;

class cli extends fields\command\stderr
{

    protected $prefix = null;
    protected $styler = null;

    /**
     * @param \autodeploy\php\sapi\cli\prefix $prefix
     * @param \autodeploy\php\sapi\cli\styler $styler
     * @param \autodeploy\php\locale $locale
     */
    public function __construct(prefix $prefix = null, styler $styler = null, locale $locale = null)
    {
        parent::__construct($locale);

        $this
            ->setPrefix($prefix ?: new prefix())
            ->setStyler($styler ?: new styler())
        ;
    }

    /**
     * @param $prefix
     * @return cli
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * @param \autodeploy\php\sapi\cli\styler $styler
     * @return cli
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
        return $this->prefix .
            (
                $this->value === null || $this->value === ''
                ?
                ''
                :
                $this->styler->colorize( $this->locale->_("ERR : " . $this->value) )
            )
        ;
    }
}
