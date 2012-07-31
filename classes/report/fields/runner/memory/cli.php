<?php

namespace autodeploy\report\fields\runner\memory;

use
    autodeploy\report\fields,
    autodeploy\php\locale,
    autodeploy\php\sapi\cli\prefix,
    autodeploy\php\sapi\cli\styler,
    autodeploy\php\sapi\cli\table
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
        $memory = $this->totalMemory;
        $scale  = 'o';
        if ($this->totalMemory > pow(1024, 2))
        {
            $memory = $this->totalMemory / pow(1024, 2);
            $scale  = 'Mo';
        }
        else if ($this->value > pow(1024, 1))
        {
            $memory = $this->totalMemory / pow(1024, 1);
            $scale  = 'Ko';
        }

        return $this->prefix .
            sprintf(
                $this->locale->_('%1$s: %2$s'),
                $this->titleStyler->colorize($this->locale->_('Total memory')),
                $this->memoryStyler->colorize(sprintf($this->locale->_('%d %s'), $memory, $scale))
            ) .
            PHP_EOL
        ;
    }
}
