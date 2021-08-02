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
        return $this->collection->resolveRoute($method, $path);
    }

}
