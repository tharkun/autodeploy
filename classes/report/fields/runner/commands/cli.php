<?php

namespace autodeploy\report\fields\runner\commands;

use
    autodeploy\report\fields,
    autodeploy\php\locale,
    autodeploy\outputs\cli\prefix,
    autodeploy\outputs\cli\styler,
    autodeploy\outputs\cli\table
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

        foreach ($this->value as $i => $val)
        {
            $this->value[$i][1] = $val[1] ? 'yes' : 'no';
        }

        return $this->prefix .
            sprintf(
                $this->locale->_('%1$s'),
                $this->titleStyler->colorize($this->locale->_('Commands summary'))
            ) .
            PHP_EOL .
            PHP_EOL .
            (string) new table( $this->value, array('Commands', 'Executed ?') )
        ;
    }
}
