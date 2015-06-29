<?php namespace framework;

/**
 * Class Router
 * @package common
 */
final class Router
{

    /**
     *  The main routing function to call in web interceptor/index
     *  The approach is simple as possible
     */
    public static function init()
    {

        /* Catch http request */
        $request = new HttpRequest();

        /* Check if reserved controller 'main' requestes */
        if ($request->queryStartsFrom('/main')) {
            /* Finish here */
            Http::sendError(404);
        }

        /* Choose controller */
        if (!$reference = self::chooseController($request))
        {
            /* Or finish here */
            Http::sendError(404);
        }

        /* Route request */
        if (!$response = self::route($reference, $request))
        {
            /* Or finish here */
            Http::sendError(404);
        }

        /* Sending final response
         * JSON
         */
        if ($response['type'] === 'json') {
            Http::sendJSON($response['data']);
        }

        /* OR plain html */
        Http::send($response['data']);
    }

    /**
     * @param HttpRequest $request
     * @return string
     */
    protected static function chooseController(HttpRequest $request)
    {
        /* Get controller name from the request */
        $ctrl = $request->getController() . 'Controller';

        /* If this controller exists - pass it or use default MainController */
        return '\application\controllers\\' . ((class_exists('\application\controllers\\'.$ctrl)) ? $ctrl : 'MainController');
    }


    /**
     * @param $reference
     * @param HttpRequest $request
     * @return bool
     */
    protected static function route($reference, HttpRequest $request)
    {

        /* Load controller */
        $controller = new $reference ($request);

        /* Verify controller */
        if (!is_subclass_of($controller, '\framework\Controller')) {
            return false;
        }

        /* Proceed */
        return $controller->initialized() ? $controller->proceed() : false;
    }

}