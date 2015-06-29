<?php namespace framework;

use \framework\helpers\StringAssist;

/**
 * Class HttpRequest
 * @package common
 */
final class HttpRequest
{
    /**
     * @var
     */
    protected $method;

    /**
     * @var array
     */
    protected $path = [];
    /**
     * @var array
     */
    protected $http_params = [];
    /**
     * @var string
     */
    protected $address = '0.0.0.0';

    /**
     * @var bool|mixed
     */
    protected $controller;

    /**
     * @var bool
     */
    protected $controller_exists = true;

    /**
     *
     */
    public function __construct()
    {

        /* Get HTTP method */
        $this->method = $_SERVER['REQUEST_METHOD'];

        /* Get requested query */
        $this->query = $_REQUEST['_QUERY'] != '/' ? $_REQUEST['_QUERY'] : 'main';

        /* Get path chunks */
        $this->path = explode('/', ltrim($this->query, '/'));

        /* Get HTTP passed params */
        $this->http_params = $_REQUEST;

        /* Get user address */
        $this->address = $this->getAddress();

        /* get CamelCase controller name */
        $this->controller = StringAssist::convertReference($this->path[0]);

    }

    /**
     * @return array
     */
    public function getHttpParams()
    {
        return $this->http_params;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return bool|mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param bool $skip_controller
     * @return array
     */
    public function getParams($skip_controller = false) {

        $params = $this->path;

        if ($skip_controller) {
            array_shift($params);
        }

        return $params;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    public function queryStartsFrom($string) {
        return (strpos($this->query, $string) === 0);
    }

}