<?php

namespace autodeploy\outputs\cli;

use autodeploy\outputs;

class styler
{

    protected static $styles = array(
        'default'           => '0',
        'bold'              => 1,
        'faint'             => 2,
        'italic'            => 3,
        'underlined'        => 4,
        'blink'             => 5,
        'blinkfast'         => 6,
        'negative'          => 7,
        'doubleunderlined'  => 21,
        'normal'            => 22,
        'notitalic'         => 23,
        'notunderlined'     => 24,
        'noblink'           => 25,
        'positive'          => 27,
    );

    protected static $fgColors = array(
        'gray'          => 30,
        'black'         => 30,
        'red'           => 31,
        'green'         => 32,
        'yellow'        => 33,
        'blue'          => 34,
        'magenta'       => 35,
        'cyan'          => 36,
        'white'         => 37,
        'default'       => 39,
    );

    protected static $bgColors = array(
        'gray'       => 40,
        'black'      => 40,
        'red'        => 41,
        'green'      => 42,
        'yellow'     => 43,
        'blue'       => 44,
        'magenta'    => 45,
        'cyan'       => 46,
        'white'      => 47,
        'default'    => 49,
    );

    private static $escape = "\033[%sm";

    protected $style = null;
    protected $fgColor = null;
    protected $bgColor = null;
    protected $cli = null;

    /**
     * @param null $style
     * @param null $fgColor
     * @param null $bgColor
     * @param \autodeploy\outputs\cli|null $cli
     */
    public function __construct($style = null, $fgColor = null, $bgColor = null, outputs\cli $cli = null)
    {
        $this
            ->setStyle($style?:'default')
            ->setForeGroundColor($fgColor?:'default')
            ->setBackGroundColor($bgColor?:'default')
            ->setCli($cli ?: new outputs\cli())
        ;
    }

    /**
     * @throws \InvalidArgumentException
     * @param $style
     * @return ground
     */
    public function setStyle($style)
    {
        if (!is_string($style) && !is_numeric($style) && !is_array($style))
        {
            throw new \InvalidArgumentException('Style parameter should be a string or an integer.');
        }

        $this->checkInput($style, self::$styles, 'Style');

        if (!is_array($style))
        {
            $style = array( $style );
        }

        foreach ($style as $key => $value)
        {
            if (is_string($value))
            {
                $style[$key] = self::$styles[ $value ];
            }
        }

        $this->style = $style;

        return $this;
    }

    /**
     * @throws \InvalidArgumentException
     * @param $input
     * @param $references
     * @param $err
     * @return void
     */
    protected function checkInput($input, $references, $err)
    {
        if (is_array($input))
        {
            foreach ($input as $value)
            {
                $this->checkInput($value, $references, $err);
            }
        }
        if (is_numeric($input))
        {
            if (!in_array($input, $references))
            {
                throw new \InvalidArgumentException(sprintf('%s parameter \'%s\' is not valid.', $err, $input));
            }
        }
        if (is_string($input))
        {
            if (!array_key_exists($input, $references))
            {
                throw new \InvalidArgumentException(sprintf('%s parameter \'%s\' is not valid.', $err, $input));
            }
        }
    }

    /**
     * @throws \InvalidArgumentException
     * @param $fgColor
     * @return ground
     */
    public function setForeGroundColor($fgColor)
    {
        if (!is_string($fgColor) && !is_numeric($fgColor))
        {
            throw new \InvalidArgumentException('Foreground parameter should be a string or an integer.');
        }

        $this->checkInput($fgColor, self::$fgColors, 'Foreground');

        if (is_string($fgColor))
        {
            $fgColor = self::$fgColors[ $fgColor ];
        }

        $this->fgColor = $fgColor;

        return $this;
    }

    /**
     * @throws \InvalidArgumentException
     * @param $bgColor
     * @return ground
     */
    public function setBackGroundColor($bgColor)
    {
        if (!is_string($bgColor) && !is_numeric($bgColor))
        {
            throw new \InvalidArgumentException('Background parameter should be a string or an integer.');
        }

        $this->checkInput($bgColor, self::$bgColors, 'Background');

        if (is_string($bgColor))
        {
            $bgColor = self::$bgColors[ $bgColor ];
        }

        $this->bgColor = $bgColor;

        return $this;
    }

    /**
     * @param \autodeploy\outputs\cli $cli
     * @return ground
     */
    public function setCli(outputs\cli $cli)
    {
        $this->cli = $cli;

        return $this;
    }

    /**
     * @param $string
     * @return string
     */
    public function colorize($string)
    {
        if ($this->cli->isInteractive() !== true)
        {
            return $string;
        }

        if ($this->style !== null || $this->fgColor !== null || $this->bgColor !== null)
        {
            $sequences = array();
            if ($this->style !== null)
            {
                $sequences = $this->style;
            }
            if ($this->fgColor !== null)
            {
                $sequences[] = $this->fgColor;
            }
            if ($this->bgColor !== null)
            {
                $sequences[] = $this->bgColor;
            }

            $string = sprintf(self::$escape, implode(';', $sequences)) . $string . sprintf(self::$escape, '0');
        }

        return $string;
    }
}
