<?php

namespace autodeploy\report\fields\step\duration;

use
    autodeploy\report\fields,
    autodeploy\php\locale,
    autodeploy\outputs\cli\prefix,
    autodeploy\outputs\cli\styler,
    autodeploy\outputs\cli\table
;

class cli extends fields\step\duration
{
    protected $prefix = null;
    protected $titleStyler = null;
    protected $durationStyler = null;

    public function __construct(prefix $prefix = null, styler $titleStyler = null, styler $durationStyler = null, locale $locale = null)
    {
        parent::__construct($locale);

        $this
            ->setPrefix($prefix ?: new prefix())
            ->setTitleStyler($titleStyler ?: new styler())
            ->setDurationStyler($durationStyler ?: new styler())
        ;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function setTitleStyler(styler $titleStyler)
    {
        $this->titleStyler = $titleStyler;

        return $this;
    }

    public function setDurationStyler(styler $durationStyler)
    {
        $this->durationStyler = $durationStyler;

        return $this;
    }

    public function __toString()
    {
        return $this->prefix .
            sprintf(
                $this->locale->_('%1$s: %2$s.'),
                $this->titleStyler->colorize($this->locale->_('Step duration')),
                $this->durationStyler->colorize($this->value === null ? $this->locale->_('unknown') : sprintf($this->locale->__('%4.4f second', '%4.4f seconds', $this->value), $this->value))
            ) .
            PHP_EOL
        ;
    }
}
