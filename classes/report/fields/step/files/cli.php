<?php

namespace autodeploy\report\fields\step\files;

use
    autodeploy\report\fields,
    autodeploy\php\locale,
    autodeploy\outputs\cli\prefix,
    autodeploy\outputs\cli\styler,
    autodeploy\outputs\cli\table
;

class cli extends fields\step\files
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
        $output = "Remaining files : " . count($this->files) . PHP_EOL;

        if (count($this->files))
        {
            foreach ($this->files as $value)
            {
                $output .= $this->prefix . '- ' . $value . PHP_EOL;
            }

        }

        return $output . PHP_EOL;
    }
}
