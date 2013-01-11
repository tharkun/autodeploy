<?php

namespace autodeploy\report\fields\step\actions;

use
    autodeploy\report\fields,
    autodeploy\php\locale,
    autodeploy\php\sapi\cli\prefix,
    autodeploy\php\sapi\cli\styler,
    autodeploy\php\sapi\cli\table
;

class cli extends fields\step\actions
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
        if (!count($this->actions))
        {
            return '';
        }

        $rows = array();
        foreach ($this->getParsers() as $parser)
        {
            $rows[] = array($parser, 0);
        }

        foreach ($this->getActions() as $action)
        {
            foreach ($rows as $key => $row)
            {
                if ($action['parser'] === $row[0])
                {
                    $rows[ $key ][ 1 ]++;
                }
            }
        }

        return (string) new table( $rows, array('Parser', 'Action(s) found') );
    }

}
