php-xframework (alfa 0.1)
============

Lightweight MVC micro framework

Features
----------
- Router (controller based approach)
- Connector (xconn)
- Templating (lightncandy)
- Application structure
- Some additional models/helpers

External components
----------
- Connector (wake-up-neo/php-xconn)
- Templating (zordius/lightncandy)

Requires
----------
- PHP 5.4+

Configuration
----------

1. Root directory: /application/web
2. Interception: /interceptor.php?_QUERY=$uri&$query_string
3. Configs: /application/config


Routing behaviour
----------

Root request goes to /application/controllers/MainController

In each extened controller you may define $listen to configure your routing

```php

    protected $listen = [];

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
```