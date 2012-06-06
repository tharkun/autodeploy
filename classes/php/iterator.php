<?php

namespace autodeploy\php;

class iterator implements \Iterator, \Countable
{

    protected $position = null;
    protected $size = 0;
    protected $collection = array();
    protected $skiped = array();

    public function __construct(array $collection = array())
    {
        $this->collection = $collection;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return (current($this->collection) !== false);
    }

    /**
     * @return mixed|null
     */
    public function current()
    {
        $value = null;

        if ($this->valid() === true)
        {
            $value = current($this->collection);
        }

        return $value;
    }

    /**
     * @return null
     */
    public function key()
    {
        return !is_integer($this->position) || $this->position < 0 || $this->position >= $this->size ? null : $this->position;
    }

    /**
     * @param int $times
     * @return iterator
     */
    public function next($times = 1)
    {
        return $this->move($times, 'next', 'next', function ($self) { return $self->key()+1; });
    }

    /**
     * @param int $times
     * @return iterator
     */
    public function prev($times = 1)
    {
        return $this->move($times, 'prev', 'prev', function ($self) { return $self->key()-1; });
    }

    /**
     * @param $times
     * @param $name
     * @param $method
     * @param $callback
     * @return iterator
     */
    protected function move($times, $name, $method, $callback)
    {
        if ($this->size)
        {
            while ($this->valid() && $times > 0)
            {
                $this->moveToValid($name, $method, $callback);

                $times--;
            }
        }

        return $this;
    }

    /**
     * @param $name
     * @param $method
     * @param $callback
     * @return iterator
     */
    protected function moveToValid($name, $method, $callback)
    {
        if ($this->size)
        {
            $name($this->collection);

            $this->position = $callback($this);

            while (in_array($this->key(), $this->skiped) && $this->valid())
            {
                $this->$method();
            }
        }

        return $this;
    }

    /**
     * @return iterator
     */
    public function rewind()
    {
        return $this->moveToValid('reset', 'next', function ($self) { return 0; });
    }

    /**
     * @return iterator
     */
    public function end()
    {
        return $this->moveToValid('end', 'prev', function ($self) { return $self->count() - 1; });
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->size;
    }

    /**
     * @return array
     */
    public function getArrayCopy()
    {
        return $this->collection;
    }

    /**
     * @return iterator
     */
    public function reset()
    {
        $this->position = null;
        $this->size = 0;
        $this->collection = array();
        $this->skiped = array();

        return $this;
    }

    /**
     * @param mixed $value
     * @return iterator
     */
    public function append($value)
    {
        $this->size++;

        $this->collection[] = $value;

        return $this;
    }

    /**
     * @return iterator
     */
    public function skip()
    {
        if (!in_array($this->key(), $this->skiped))
        {
            $this->skiped[] = $this->key();
            $this->collection[ $this->key() ] = null;
        }

        return $this;
    }

    /**
     * @param $value
     * @return void
     */
    public function set($value)
    {
        $this->collection[ $this->key() ] = $value;

        return $this;
    }

}
