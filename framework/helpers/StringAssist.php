<?php namespace framework\helpers;

/**
 * Class StringAssist
 * @package helpers
 */
final class StringAssist
{

    /**
     * @param $string
     * @return bool|mixed
     */
    public static function convertReference($string)
    {
        if (!preg_match("/^[a-zA-Z0-9-]+$/", $string)) {
            return false;
        }

        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

}