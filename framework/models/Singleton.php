<?php namespace framework\models;

class Singleton
{

    protected static $instance;

    static function getInstance() {
        $_ = get_called_class();
        return isset (self::$instance[$_]) ? self::$instance[$_] : self::$instance[$_] = new $_;
    }
}