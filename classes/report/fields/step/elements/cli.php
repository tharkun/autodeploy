<?php

namespace autodeploy\report\fields\step\elements;

use
    autodeploy\report\fields,
    autodeploy\php\locale,
    autodeploy\php\sapi\cli\prefix,
    autodeploy\php\sapi\cli\styler,
    autodeploy\php\sapi\cli\table
;

class cli extends fields\step\elements
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
        $output = "Remaining elements : " . count($this->values) . PHP_EOL;

        if (count($this->values))
        {
            foreach ($this->values as $value)
            {
                $output .= $this->prefix . '- ' . $value . PHP_EOL;
            }

        }

        return $output . PHP_EOL;
    }
}
