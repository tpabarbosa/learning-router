<?php

namespace Learning;

class Router
{
    /**
     * The current registered callbacks by routes methods and paths
     * routes[method][path] = [callback, callback_params]
     *
     * @var array[]
     */
    private $routes = array();

    /**
     * Register a GET route
     *
     * @param string $path
     * @param string|array|\Closure $callback
     * @param array $callback_params
     * @return void
     */
    public function get(string $path, $callback, array $callback_params=[])
    {
        $this->add('get', $path, $callback, $callback_params);
    }

    /**
     * Register a POST route
     *
     * @param string $path
     * @param string|array|\Closure $callback
     * @param array $callback_params
     * @return void
     */
    public function post(string $path, $callback, array $callback_params=[])
    {
        $this->add('post', $path, $callback, $callback_params);
    }

    /**
     * Register a route to router
     *
     * @param string $method get|post
     * @param string $path
     * @param string|array|\Closure $callback
     * @param array $callback_params
     * @return void
     */
    private function add(string $method, string $path, $callback, array $callback_params=[])
    {
        $this->routes[$method][$path] = ['callback' => $callback, 'callback_params' => $callback_params];
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
     * @return string|array|\Closure|null
     */
    public function resolve(string $method, string $path)
    {
        return $this->routes[$method][$path] ?? null;
    }
}
