<?php namespace framework\adapters;

/**
 * Class MySQL
 *
 * @package xconn\adapters
 */
final class MySQL extends \framework\Adapter
{

    /**
     * @return mixed
     */
    public function getInsertedID()
    {
        return $this->connection->insert_id;
    }

    /**
     * @param $str
     *
     * @return string
     */
    public function quote($str)
    {
        return "'" . $this->escapeStr($str) . "'";
    }

    /**
     * @param $str
     *
     * @return string
     */
    public function escapeStr($str)
    {
        return $this->connection instanceof \MySQLi ? $this->connection->real_escape_string($str) : '';
    }

    /**
     * @param $query
     *
     * @return mysqli_result
     */
    public function query($query)
    {
        return ($this->connection instanceof \MySQLi) ? $this->connection->query($query) : false;
    }

    /**
     * @param $result
     *
     * @return bool|integer
     */
    public function rows($result)
    {
        return $result instanceof \mysqli_result ? @$result->num_rows : false;
    }

    /**
     * @param $result
     *
     * @return bool|array
     */
    public function fetch($result)
    {
        return $result instanceof \mysqli_result ? ($result->fetch_assoc() ?: false) : false;
    }

    /**
     * @param $config
     *
     * @return \mysqli
     */
    protected function openConnection($config)
    {
        return new \mysqli($config['host'], $config['user'], $config['pass'], $config['name'], $config['port'], isset ($config['socket']) ? $config['socket'] : null);
    }

    /**
     * @return bool
     */
    protected function closeConnection()
    {

        if (!$this->connection) {
            return false;
        }

        $this->connection->close();
    }

    public function getLastError() {
        return ($this->connection instanceof \MySQLi) ? $this->connection->error : false;
    }
}