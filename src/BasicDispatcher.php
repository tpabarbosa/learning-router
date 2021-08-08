<?php

namespace tpab\Router;

class BasicDispatcher implements DispatcherInterface
{
    private $routes = array();

    public function add($route, $callback)
    {
        $this->routes[$route] = $callback;
    }

    public function dispatch($route, $parameters)
    {
        //TODO: basic implementation
        // $callback = $this->routes[$route];
        // if (is_string($callback)) {
        //     return $callback;
        // }

        // if (is_array($callback)) {
        //     $const_params = array_values($const_params);
        //     $callback[0] = new $callback[0](...$const_params);
        //     return call_user_func_array($callback, $parameters);
        // } else {
        //     return call_user_func_array($callback, array_merge($parameters, $const_params));
        // }
    }

}
