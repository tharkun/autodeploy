<?php

namespace autodeploy\report\fields\runner\commands;

use
    autodeploy\report\fields,
    autodeploy\php\locale,
    autodeploy\php\sapi\cli\prefix,
    autodeploy\php\sapi\cli\styler,
    autodeploy\php\sapi\cli\table
;

class cli extends fields\runner\commands
{
    protected $prefix = null;
    protected $titleStyler = null;

    public function __construct(prefix $prefix = null, styler $titleStyler = null, locale $locale = null)
    {
        parent::__construct($locale);

        $this
            ->setPrefix($prefix ?: new prefix())
            ->setTitleStyler($titleStyler ?: new styler())
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

    public function __toString()
    {
        if ($this->value === null)
        {
            return '';
        }

        $values = array();
        foreach ($this->value as $i => $val)
        {
            $values[] = array(
                $val[1] ? 'yes' : 'no',
                $val[0]
            );
        }

        return $this->prefix .
            sprintf(
                $this->locale->_('%1$s'),
                $this->titleStyler->colorize($this->locale->_('Commands summary'))
            ) .
            PHP_EOL .
            PHP_EOL .
            (string) new table( $values, array('Commands', 'Executed ?') )
        ;
    }
}
