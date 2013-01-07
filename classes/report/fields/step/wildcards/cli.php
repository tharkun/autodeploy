<?php

namespace autodeploy\report\fields\step\wildcards;

use
    autodeploy\report\fields,
    autodeploy\php\locale,
    autodeploy\php\sapi\cli\prefix,
    autodeploy\php\sapi\cli\styler,
    autodeploy\php\sapi\cli\table
;

class cli extends fields\step\wildcards
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
        if (!count($this->iterator))
        {
            return '';
        }

        $array = $this->iterator->getArrayCopy();

        array_walk($array, function(&$val,$key) {
            $val = array(
                $val['profile'],
                $val['parser'],
                $val['type'],
                //$val['value'],
                //$val['command'],
                //$val['wildcard'],
                implode('', $val['todo']->getWildcards())
            );
        });

        return (string) new table($array, array('Profile', 'Parser', 'Type', /*'Value', 'Command', */'Wildcard') );
    }

}
