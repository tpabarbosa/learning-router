<?php

namespace tpab\Router;

use tpab\Router\Route;

class RouteGroup
{
    private $collection = array();
    
    private $group_path;

    /**
     * Group of routes within same base_path
     *
     * @var RouteGroup[]
     */
    private $groups = array();

    public function __construct(string $group_path)
    {
        $this->group_path = $group_path;
        $this->collection = new RouteCollection();
        
    }

    public function group($group_path)
    {
        $group = new RouteGroup($group_path);
        $this->groups[] = $group;
        return $group;
    }

    public function add($methods, string $path, $callback, array $callback_params = [])
    {
        $path = strlen($path) == 1 && $this->group_path != '' ? '' : $path;
        $group_path = strlen($this->group_path) == 1 ? '' : $this->group_path;
        $path = $group_path . $path;
        $this->collection->addRoute(new Route($methods, $path, $callback, $callback_params));
        return $this;
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
        return $this->add('GET', $path, $callback, $callback_params);
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
        return $this->add('POST', $path, $callback, $callback_params);
    }

    public function resolve($method, $path)
    {
        return $this->collection->resolveRoute($method, $path, $this->groups);
    }

}
