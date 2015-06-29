<?php namespace framework\helpers;

/**
 * Class SystemExit
 * @package framework\helpers
 */
final class SystemExit
{

    public static function finishExecution() {

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        } else {
            exit(0);
        }
    }
}