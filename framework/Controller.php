<?php namespace framework;

use framework\helpers\StringAssist;

/**
 * Class Controller
 * @package common
 */

abstract class Controller
{

    /**
     *      @listen - params for routing
     *
     *      >> NO ACTIONS DEFINED BEHAVIOUR
     *
     *      Example configuration: ['x', 'y']
     *
     *      PATH                        Main action Called and Params passed through getParam(name);
     *
     *      /controller/x/y/z           ->404
     *      /controller/x/y             ->main(x,y)
     *      /controller/x               ->main(x,null)
     *      /controller                 ->main(null,null)
     *
     *
     *      >> ACTIONS DEFINED BEHAVIOUR (use @ sign, once)
     *      if actionMethod (relevant to @ position) is defined
     *          will route to appropriate actionMethod
     *      else
     *          404
     *
     *      Example configuration: ['@', 'x', 'y', 'z']
     *
     *      PATH                        Defined action Called and Params passed through getParam(name);
     *
     *      /controller/action/x/y/z/a  ->404
     *      /controller/action/x/y/z    ->action(x,y,z)
     *      /controller/action/x/y      ->action(x,y,null)
     *      /controller/action/x        ->action(x,null,null)
     *      /controller/action          ->action(null,null,null)
     *      /controller                 ->main(null,null,null)
     *
     *
     *      Example configuration: ['x', '@', 'y', 'z']
     *      (Action after param(s))
     *
     *      PATH                        Action Called and Params passed through getParam(name);
     *
     *      /controller/x/action/y/z/a  ->404
     *      /controller/x/action/y/z    ->action(x,y,z)
     *      /controller/x/action/y      ->action(x,y,null)
     *      /controller/x/action/       ->action(x,null,null)
     *      /controller/x               ->main(x,null,null)
     *      /controller                 ->main(null,null,null)
     *
     *
     *      >> IF CONTROLLER NOT FOUND
     *
     *      Example configuration: MainController ['x']
     *
     *      /controller/x               ->404
     *      /controller                 MainController->main(x = controller)
     *
     *
     */

    /* The only setting to define in child controller */
    /**
     * @var array
     */
    protected $listen = [];

    /* The type of returning results. Make influence on headers.
     * Possible options: html, json
     */
    /**
     * @var string
     */
    protected $controller_type = 'html';

    /**
     * @string HTTP Method that was requested
     */
    protected $method;

    /* The action to run */
    /**
     * @var null|string
     */
    protected $action = null;

    /**
     * Passed params from the route logic
     * @var array
     */
    protected $params = [];

    /**
     * Passed params from http request
     * @var array
     */
    protected $http_params = [];

    /**
     * default controller action
     * @return mixed
     */
    abstract protected function main();

    /**
     * @param HttpRequest $request
     */
    public function __construct(HttpRequest $request)
    {

        /* Check for wrong 'listen' configuration */
        if (!is_array($this->listen)) {
            return false;
        }

        /* Get HTTP method */
        $this->method = $request->getMethod();

        /* Get HTTP params */
        $this->http_params = $request->getHttpParams();

        /* Get passed params */
        $passedParams = $request->getParams(
            /* Check if this controller was requested so we skip the first param */
            $this->matchControllerWithRequested($request)
        );

        /* If no params was defined to listen */
        if ($this->listen === []) {

            /* If params were not passed */
            if ($passedParams === []) {

                /* Proxy to main action */
                $this->action = 'main';
            }

        } else {

            /* If params count same or less than we listening for */
            if (count($this->listen) >= count($passedParams)) {

                /* Define behaviour */
                $behaviour = $this->getBehaviour($passedParams);

                /* Set action */
                $this->action = $behaviour['action'] ? ('action' . StringAssist::convertReference($behaviour['action'])) : 'main';

                /* Set params */
                $this->params = $behaviour['params'];

            }
        }
    }


    /**
     * Check if this current controller the same that was requested
     * @param $request
     * @return bool
     */
    public function matchControllerWithRequested (HttpRequest $request) {
        return str_replace ('application\controllers\\','', get_called_class()) === ($request->getController() .'Controller');
    }

    /**
     * Proxy function to controller selected action
     * @return array|bool
     */
    public function proceed()
    {

        if (!$this->initialized()) {
            return false;
        }

        /* Get defined action */
        $action_reference = $this->action;

        /* Check if the requested action exists */
        if (method_exists($this, $action_reference)) {

            /* Run action */
            $response = $this->$action_reference();
        } else {

            /* Else 404 */
            return false;
        }

        /* Return data with appropriate type */
        return $response ? ['type' => $this->controller_type, 'data' => $response] : false;
    }

    /**
     * Check if action was assigned during construct
     * @return bool
     */
    public function initialized()
    {
        return ($this->action !== null);
    }

    /**
     *
     * Compare 'listen' rule with passed params
     *
     * @param $passedParams
     * @return array
     */
    protected function getBehaviour($passedParams)
    {

        /* Final list */
        $list = [];
        $action = null;

        /* Traversing each listened param */
        foreach ($this->listen as $index => $key) {

            /* If this index is action - skip it */
            if ($key === '@') {
                $action = isset ($passedParams[$index]) ? $passedParams[$index] : null;
                continue;
            }

            /* Adding param */
            $list[$key] = isset ($passedParams[$index]) ? $passedParams[$index] : null;
        }

        return ['action' => $action, 'params' => $list];
    }

    /**
     *
     * Method for safely getting param in child controllers' action
     *
     * @param $key
     * @return bool
     */
    protected function getParam($key)
    {
        return (isset ($this->params[$key])) ? $this->params[$key] : false;
    }

    /**
     * Helper for imploding array of HTML content
     * @param array $content
     * @return string
     */
    protected function toHTML(array $content)
    {
        return implode("\n", $content);
    }
}