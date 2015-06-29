<?php namespace framework\models;

/**
 * Class SimpleArray
 *
 * @package models
 */

class SimpleArray implements \ArrayAccess, \IteratorAggregate
{

    /**
     * @var array
     */
    protected $container = [];

    /**
     * @return array
     */
    public function asArray()
    {
        return $this->container;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     *
     * @return mixed
     */
    public function offsetSet($offset, $value)
    {
        return $this->container[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset ($this->container[$offset]);
        }
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset ($this->container[$offset]);
    }

    /**
     * @param mixed $offset
     *
     * @return null
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->container[$offset] : null;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->container);
    }
}