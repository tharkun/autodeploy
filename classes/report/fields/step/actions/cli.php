<?php

namespace autodeploy\report\fields\step\actions;

use
    autodeploy\report\fields,
    autodeploy\php\locale,
    autodeploy\outputs\cli\prefix,
    autodeploy\outputs\cli\styler,
    autodeploy\outputs\cli\table
;

class cli extends fields\step\actions
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
