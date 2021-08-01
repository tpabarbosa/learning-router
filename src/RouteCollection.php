<?php

namespace tpab\Router;

class RouteCollection
{
    private $routes = array();

    public function addRoute(Route $route)
    {
        $found_route = $this->findRoute($route->path());
        if ($found_route) {
            $methods = $route->methods();
            $callback = $route->callback($methods[0]);
            $callback_params = $route->callbackParams($methods[0]);
            $this->addRouteMethods($found_route, $methods, $callback, $callback_params);
        } else {
            $this->routes[] = $route;
        }
    }

    private function addRouteMethods($route, $methods, $callback, $callback_params)
    {
        $route->addMethods($methods, $callback, $callback_params);
    }
    
    public function findRoute($path)
    {
        $found = array_filter($this->routes, function($route) use ($path) {
            preg_match($route->regex(), $path, $matches);
            if (!empty($matches[0])) {
                return true;
            }
            return false;
        });
        return reset($found);
    }

    public function hasRoute($path) 
    {
        return !empty($this->findRoute($path));
    }

    public function findRoutesWithParameters()
    {
        return array_filter($this->routes, function($route) {
            if ($route->parameters() !== []) {
                return true;
            }
            return false;
        });
    }

}
