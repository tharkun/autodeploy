<?php

namespace autodeploy;

class report implements aggregators\php\adapter, aggregators\php\locale, definitions\php\observer
{

    protected $title = null;

    protected $adapter = null;
    protected $locale = null;

    protected $writers = array();
    protected $fields = array();
    protected $lastSetFields = array();
    protected $string = '';

    public function __construct(php\adapter $adapter = null, php\locale $locale = null)
    {
        $this
            ->setAdapter($adapter ?: new php\adapter())
            ->setLocale($locale ?: new php\locale())
        ;
    }

    public function setTitle($title)
    {
        $this->title = (string) $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setLocale(php\locale $locale)
    {
        $this->locale = $locale;

        return $this;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setAdapter(php\adapter $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function addField(report\field $field)
    {
        $this->fields[] = $field;

        return $this;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getWriters()
    {
        return $this->writers;
    }

    public function handleEvent($event, definitions\php\observable $observable)
    {
        $this->lastSetFields = array();

        foreach ($this->fields as $field)
        {
            if ($field->handleEvent($event, $observable) === true)
            {
                $this->lastSetFields[] = $field;
            }
        }

        return $this;
    }

    public function __toString()
    {
        $string = '';

        foreach ($this->lastSetFields as $field)
        {
            $string .= $field;
        }

        return $string;
    }

    public function addWriter(definitions\writer $writer)
    {
        return $this->doAddWriter($writer);
    }

    protected function doAddWriter($writer)
    {
        $this->writers[] = $writer;

        return $this;
    }
}
