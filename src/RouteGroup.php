<?php

namespace tpab\Router;

class RouteGroup
{
    private $collection = array();
    private $group_path;

    public function __construct($group_path)
    {
        $this->collection = new RouteCollection();
        $this->group_path = $group_path;
    }

    public function add($methods, string $path, $callback, array $callback_params = [])
    {
        $path = strlen($path) == 1 ? '' : $path;
        $this->collection->addRoute(new Route($methods, $this->group_path . $path, $callback, $callback_params));

        return $this;
    }

    public function hasRoute($method, $path)
    {
        $status = RouteResolved::PATH_NOT_FOUND;
        $method = strtoupper($method);
        $path = strlen($path) > 1 ? rtrim($path, '/') : $path;
        
        if ($this->collection->hasRoute($path)) {
            $route = $this->collection->findRoute($path);
            $status = RouteResolved::METHOD_NOT_ALLOWED;
            $allowed_methods = $route->methods();

            if (in_array($method, $route->methods())) {
                $status = RouteResolved::FOUND;
                $callback = $route->callback($method);
                $path_params = $this->resolveParams($path, $route);
                $callback_params = $route->callbackParams($method);
            }
            $resolved = compact('status', 'method', 'path', 'allowed_methods', 'path_params', 'callback', 'callback_params');
            return new RouteResolved($resolved);
        }
        
        return false;
    }
    private function resolveParams($path, $route) 
    {
        $parts = explode('/', ltrim($path, '/'));
        $path_params = [];

        foreach ($route->parts() as $key => $value) {
            if ($parts[$key] !== $value) {
                $path_params[$value] = $parts[$key];
            }
        };
        return $path_params;
    }
}
