<?php namespace framework\helpers;

/**
 * Class IniConfig
 *
 * @package helpers
 */
final class IniConfig
{

    const CONFIG_PATH = "/application/config/";

    public static function loadValue($config_name, $section, $key)
    {

        $config = self::load($config_name, $section);

        if ($config) {
            return isset ($config[$key]) ? $config[$key] : null;
        }

        return null;
    }

    /**
     * @param $filepath
     * @param string $section
     *
     * @return array|bool
     */
    public static function load($config_name, $section = null)
    {
        $filepath = str_replace('framework/helpers', '', __DIR__) . self::CONFIG_PATH . $config_name . '.ini';

        if (file_exists($filepath)) {

            $result = parse_ini_file($filepath, true);

            if ($section) {
                return isset ($result[$section]) ? $result[$section] : false;
            }

            return $result;

        } else {
            return false;
        }
    }

}