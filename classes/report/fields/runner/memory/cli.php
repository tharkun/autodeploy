<?php

namespace autodeploy\report\fields\runner\memory;

use
    autodeploy\report\fields,
    autodeploy\php\locale,
    autodeploy\outputs\cli\prefix,
    autodeploy\outputs\cli\styler,
    autodeploy\outputs\cli\table
;

class cli extends fields\runner\memory
{
    protected $prefix = null;
    protected $titleStyler = null;
    protected $memoryStyler = null;

    public function __construct(prefix $prefix = null, styler $titleStyler = null, styler $memoryStyler = null, locale $locale = null)
    {
        parent::__construct($locale);

        $this
            ->setPrefix($prefix ?: new prefix())
            ->setTitleStyler($titleStyler ?: new styler())
            ->setMemoryStyler($memoryStyler ?: new styler())
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

    public function setMemoryStyler(styler $memoryStyler)
    {
        $this->memoryStyler = $memoryStyler;

        return $this;
    }

    public function __toString()
    {
        $memory = $this->value;
        $scale  = 'o';
        if ($this->value > pow(1024, 2))
        {
            $memory = $this->value / pow(1024, 2);
            $scale  = 'Mo';
        }
        else if ($this->value > pow(1024, 1))
        {
            $memory = $this->value / pow(1024, 1);
            $scale  = 'Ko';
        }

        return $this->prefix .
            sprintf(
                $this->locale->_('%1$s: %2$s'),
                $this->titleStyler->colorize($this->locale->_('Total memory')),
                $this->memoryStyler->colorize($this->value === null ? $this->locale->_('unknown') : sprintf($this->locale->_('%d %s'), $memory, $scale))
            ) .
            PHP_EOL
        ;
    }
}
