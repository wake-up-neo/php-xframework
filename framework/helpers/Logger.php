<?php namespace framework\helpers;

final class Logger
{
    public static function log($data)
    {

        $f = fopen(self::getLogPath() . 'manual.log', 'a');
        fwrite($f, '[' . date('Y-m-d h:i:s') . '] ' . (is_array($data) ? implode(';', $data) : $data) . "\r\n");
        fclose($f);
    }

    public static function getLogPath()
    {
        return (__DIR__  . '/../../application/runtime/');
    }

    public static function syslog($data)
    {
        $f = fopen(self::getLogPath() . 'system.log', 'a');
        fwrite($f, '[' . date('Y-m-d h:i:s') . '] ' . (is_array($data) ? implode(';', $data) : $data) . "\r\n");
        fclose($f);
    }

    public static function multiImplode($arr)
    {
        if (!is_array($arr)) {
            return '';
        }
        $result = '';
        foreach ($arr as $key => $val) {
            $result .= is_array($val) ? self::multiImplode($val) : $key . ':' . $val . ';';
        }

        return $result;
    }
}