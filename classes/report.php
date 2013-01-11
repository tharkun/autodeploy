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

    /**
     * @param php\adapter $adapter
     * @param php\locale $locale
     */
    public function __construct(php\adapter $adapter = null, php\locale $locale = null)
    {
        $this
            ->setAdapter($adapter ?: new php\adapter())
            ->setLocale($locale ?: new php\locale())
        ;
    }

    /**
     * @param $title
     * @return report
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;

        return $this;
    }

    /**
     * @return null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param php\locale $locale
     * @return report|void
     */
    public function setLocale(php\locale $locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return null|void
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param php\adapter $adapter
     * @return report|void
     */
    public function setAdapter(php\adapter $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * @return null|void
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param report\field $field
     * @return report
     */
    public function addField(report\field $field)
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return array
     */
    public function getWriters()
    {
        return $this->writers;
    }

    /**
     * @param $event
     * @param definitions\php\observable $observable
     * @return report
     */
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

    /**
     * @return string
     */
    public function __toString()
    {
        $string = '';

        foreach ($this->lastSetFields as $field)
        {
            $string .= $field;
        }

        return $string;
    }

    /**
     * @param definitions\writer $writer
     * @return report
     */
    public function addWriter(definitions\writer $writer)
    {
        return $this->doAddWriter($writer);
    }

    /**
     * @param $writer
     * @return report
     */
    protected function doAddWriter($writer)
    {
        $this->writers[] = $writer;

        return $this;
    }
}
