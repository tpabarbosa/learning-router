<?php

namespace Learning;

class Router
{
    /**
     * The current registered callbacks by routes methods and paths
     * routes[method][path] = callback
     *
     * @var string|array|\Closure[]
     */
    private $routes = array();

    /**
     * Register a GET route
     *
     * @param string $path
     * @param string|array|\Closure $callback
     * @return void
     */
    public function get(string $path, $callback)
    {
        $this->add('get', $path, $callback);
    }

    /**
     * Register a POST route
     *
     * @param string $path
     * @param string|array|\Closure $callback
     * @return void
     */
    public function post(string $path, $callback)
    {
        $this->add('post', $path, $callback);
    }

    /**
     * Register a route to router
     *
     * @param string $method get|post
     * @param string $path
     * @param string|array|\Closure $callback
     * @return void
     */
    private function add(string $method, string $path, $callback)
    {
        $this->routes[$method][$path] = $callback;
    }

    /**
     * Determine if router has a route registered
     *
     * @param string $method
     * @param string $path
     * @return bool
     */
    public function hasRoute(string $method, string $path): bool
    {
        return isset($this->routes[strtolower($method)][$path]);
    }

    /**
     * Returns callback for the route
     *
     * @param string $method
     * @param string $path
     * @return mixed 
     */
    public function resolve(string $method, string $path)
    {
        $route = 'Page Not Found';

        if ($this->hasRoute($method, $path)) {
            $route = $this->routes[$method][$path];
        }

        if (is_string($route)) {
            return $route;
        }
        
        if (is_array($route)) {
            $route[0] = new $route[0]();
        }
        
        return call_user_func_array($route, []);

    }
}
