<?php namespace framework;

use framework\helpers\IniConfig;
use framework\helpers\SystemExit;

/**
 * Class Http
 *
 * @package helpers
 */
final class Http
{

    protected static $errors = [
        400 => "Bad Request",
        401 => "Unauthorized",
        403 => "Forbidden",
        404 => "Not Found",
        422 => "Unprocessable Entity",
        429 => "Too Many Requests",
        500 => "Internal Server Error",
        503 => "Service Temporarily Unavailable"
    ];

    /**
     * @param $response
     */
    public static function sendJSON($response)
    {

        // Preset output options
        self::preset();

        // Clear buffer
        self::clearBuffer();

        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? 'https' : 'http';

        // send request options
        self::sendOptions();

        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');

        // Sending request
        echo json_encode($response + ['rs' => gethostname(), 'ts' => $protocol]);

        // Flushing
        self::flushAll();

        // Finishing execution
        SystemExit::finishExecution();

    }

    public static function sendContent (array $content) {
        self::send (implode("\n", $content));
    }

    public static function send($html) {

        // Preset output options
        self::preset();

        // Clear buffer
        self::clearBuffer();

        // Sending HTML
        echo $html;

        // Flushing
        self::flushAll();

        // Finishing execution
        SystemExit::finishExecution();
    }

    /**
     *
     */
    protected static function preset()
    {
        ini_set('zlib.output_compression', true);
        ini_set('zlib.output_compression_level', 6);
        ini_set('implicit_flush', 1);
    }

    /**
     *
     */
    protected static function clearBuffer()
    {
        while (ob_get_level()) ob_end_clean();
        ignore_user_abort();
        ob_start('ob_gzhandler');
    }

    public static function sendOptions()
    {

        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? 'https' : 'http';

        header('Access-Control-Allow-Origin: ' . self::getAllowedOrigin($protocol));
        header('Access-Control-Allow-Methods: POST, GET');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');

    }

    protected static function getAllowedOrigin($protocol)
    {
        return IniConfig::loadValue('cors', 'origin', $protocol);
    }

    /**
     *
     */
    protected static function flushAll()
    {
        ob_end_flush();
        flush();
    }

    /**
     * @param $error_code
     */
    public static function sendError($error_code)
    {

        if (!$error_code || !isset (self:: $errors[$error_code])) {
            $error_code = 404;
        }

        // Clear buffer
        self::clearBuffer();

        // Sending headers
        header('HTTP/1.1 ' . $error_code . ' ' . self:: $errors[$error_code]);

        // send request options
        self::sendOptions();

        // Sending content
        echo '<center><h1>' . $error_code . ' ' . self:: $errors[$error_code] . '</h1>'
            . '<hr />' . gethostname() . '</center>';

        // Finishing execution
        SystemExit::finishExecution();
    }

}