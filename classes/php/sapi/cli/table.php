<?php

namespace autodeploy\outputs\cli;

class table
{

    protected $headers = array();
    protected $rows = array();

    private $widths = array();

    public function __construct(array $rows, array $headers = null)
    {
        $this
            ->setRows($rows)
            ->setHeaders($headers ? : array_keys($this->rows[0]))
        ;
    }

    public function setRows(array $rows)
    {
        $this->rows = $rows;

        return $this;
    }

    public function getRows()
    {
        return $this->getRows();
    }

    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function __toString()
    {
        $this->widths = array_fill_keys(array_keys($this->rows[0]), 0);
        foreach ($this->rows as $row)
        {
            foreach ($row as $key => $value)
            {
                $this->widths[ $key ] = max($this->widths[ $key ], strlen($value));
            }
        }

        reset($this->headers);
        foreach ($this->widths as $key => $value)
        {
            $this->widths[ $key ] = max($this->widths[ $key ], strlen(current($this->headers)));
            next($this->headers);
        }

        return $this->makeEmptyAsString()
            . $this->makeHeaderAsString()
            . $this->makeEmptyAsString()
            . $this->makeContentAsString()
            . $this->makeEmptyAsString()
            . PHP_EOL
        ;
    }

    public function makeEmptyAsString()
    {
        return sprintf('+%s+', str_pad('', array_sum($this->widths) + 3*count($this->widths) - 1, '-')) . PHP_EOL;
    }

    public function makeHeaderAsString()
    {
        reset($this->widths);

        $head = '';
        foreach ($this->headers as $value)
        {
            $diff = current($this->widths) - strlen( $value );
            $head .= sprintf('| %s ', str_pad('', floor($diff/2), ' ') . $value . str_pad('', ceil($diff/2), ' '));

            next($this->widths);
        }

        return $head . '|' . PHP_EOL;
    }

    public function makeContentAsString()
    {
        $content = '';
        foreach ($this->rows as $row)
        {
            foreach ($row as $key => $value)
            {
                $content .= sprintf('| %s ', str_pad($value, $this->widths[$key], ' '));
            }
            $content .= '|' . PHP_EOL;
        }

        return $content;
    }

}
