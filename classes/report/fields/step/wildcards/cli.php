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
        if (!count($this->iterator))
        {
            return '';
        }

        $array = $this->iterator->getArrayCopy();

        array_walk($array, function(&$val,$key) {
            $val = array(
                $val['profile'],
                $val['parser'],
                implode('', $val['command']->getWildcards())
            );
        });

        return (string) new table($array, array('Profile', 'Parser', 'Wildcard') );
    }

}
