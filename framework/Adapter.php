<?php namespace framework;

/**
 * Class Adapter
 *
 * @package xconn
 */
abstract class Adapter
{

    /**
     * @var null
     */
    protected $connection = null;

    /**
     * @param $config
     */
    public function __construct($config)
    {
        $this->connection = $this->openConnection($config);
    }

    /**
     * @param $config
     * @return mixed
     */
    abstract protected function openConnection($config);

    /**
     *
     */
    public function __destruct()
    {
        $this->closeConnection();
        $this->connection = null;
    }

    /**
     * @return mixed
     */
    abstract protected function closeConnection();

    /**
     * @return bool
     */
    public function connected()
    {
        return (is_object($this->connection));
    }

    /**
     * @return mixed|null
     */
    public function proxyConnection()
    {
        return $this->connection;
    }
}