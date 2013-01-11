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

    /**
     * @param \autodeploy\php\sapi\cli\prefix $prefix
     * @param \autodeploy\php\sapi\cli\styler $titleStyler
     * @param \autodeploy\php\locale $locale
     */
    public function __construct(prefix $prefix = null, styler $titleStyler = null, locale $locale = null)
    {
        parent::__construct($locale);

        $this
            ->setPrefix($prefix ?: new prefix())
            ->setTitleStyler($titleStyler ?: new styler())
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
     * @param \autodeploy\php\sapi\cli\styler $titleStyler
     * @return cli
     */
    public function setTitleStyler(styler $titleStyler)
    {
        $this->titleStyler = $titleStyler;

        return $this;
    }

    /**
     * @return string
     */
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
            (string) new table( $values, array('Executed ?', 'Commands') )
        ;
    }
}
