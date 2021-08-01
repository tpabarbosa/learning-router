<?php

namespace tpab\Router;

use tpab\Router\Route;
use tpab\Router\RouteResolved;
use tpab\Router\DispatcherInterface;
use tpab\Router\DispatcherNotAssignedException;

class Router
{
    /**
     * The dispatcher
     *
     * @var DispatcherInterface
     */
    private $dispatcher;

    /**
     * Collection of Route instances
     *
     * @var RouteCollection
     */
    private $collection;

    private $groups = array();

    /**
     *
     * @param DispatcherInterface $dispatcher
     */
    public function __construct(DispatcherInterface $dispatcher = null)
    {
        $this->dispatcher = $dispatcher;
        $this->collection = new RouteCollection();
    }

    /**
     * Register a GET route
     *
     * @param string $path
     * @param string|array|\Closure $callback
     * @return void
     */
    public function get(string $path, $callback, $callback_params = array())
    {
        $this->add('GET', $path, $callback, $callback_params);
    }

    /**
     * Register a POST route
     *
     * @param string $path
     * @param string|array|\Closure $callback
     * @return void
     */
    public function post(string $path, $callback, $callback_params = array())
    {
        $this->add('POST', $path, $callback, $callback_params);
    }

    /**
     * Register a route to router collection
     *
     * @param string|array $methods get|post
     * @param string $path
     * @param string|array|\Closure $callback
     * @param array $callback_params
     * @return void
     */
    public function add($methods, string $path, $callback, array $callback_params = [])
    {
        $this->collection->addRoute(new Route($methods, $path, $callback, $callback_params));
    }

    public function group($group_path)
    {
        $group = new RouteGroup($group_path);
        $this->groups[] = $group;
        return $group;
    }
    /**
     * Returns resolved array of actual route
     *
     * @param string $method
     * @param string $path
     * @return mixed
     */
    public function resolve(string $method, string $path)
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

            
            
        } else {
            foreach ($this->groups as $group) {
                if ($group->hasRoute($method, $path)) {
                    return $group->hasRoute($method, $path);
                }
            }
        }
        $resolved = compact('status', 'method', 'path', 'allowed_methods', 'path_params', 'callback', 'callback_params');
        
        return new RouteResolved($resolved);
        
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

    public function dispatch(string $method, string $path)
    {
        if (! $this->dispatcher) {
            throw new DispatcherNotAssignedException();
        }

        $resolved_route = $this->resolve($method, $path);

        return $this->dispatcher->dispatch($resolved_route, 'a');
    }
}
