<?php namespace framework\models;

/**
 * Class SmartList
 *
 * @package models
 */
class SmartList extends \framework\models\SimpleArray
{

    /**
     * clear container
     */
    public function clear()
    {
        $this->container = [];
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->container[$key] = $value;
    }

    /**
     * @return mixed
     */
    public function getFirst()
    {
        return reset($container);
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->container = $data;
    }

    /**
     * @param $value
     */
    public function append($value)
    {
        $this->container[] = $value;
    }

    /**
     * @param $key
     * @param $value
     */
    public function appendTo($key, $value)
    {
        $this->container[$key][] = $value;
    }

    /**
     * @param array $array
     */
    public function merge(array $array)
    {
        $this->container += $array;
    }

    /**
     * @param $key
     * @param array $array
     */
    public function mergeWith($key, array $array)
    {
        $this->container[$key] = (isset ($this->container[$key]) ? $this->container[$key] : []) + $array;
    }

    /**
     * @param $key
     * @param array $array
     */
    public function replaceSomeWith($key, array $array)
    {
        $this->container[$key] = array_replace_recursive((isset ($this->container[$key]) ? $this->container[$key] : []), $array);
    }

    /**
     * @param int $limit
     *
     * @return array
     */
    public function each($limit = 0)
    {
        return $limit > 0 ? array_slice(array_keys($this->container), 0, $limit) : array_keys($this->container);
    }

    /**
     * @return array
     */
    public function data()
    {
        return $this->container;
    }

    /**
     * @return bool|int
     */
    public function count()
    {
        return count($this->container) > 0 ? count($this->container) : false;
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function exists($key)
    {
        return isset ($this->container[$key]);
    }

    /**
     * @param $key
     */
    public function remove($key)
    {
        if ($this->get($key)) {
            unset ($this->container[$key]);
        }
    }

    /**
     * @param $key
     *
     * @return mixed or null
     */
    public function get($key)
    {
        return (isset($this->container[$key])) ? $this->container[$key] : null;
    }


}