<?php

namespace tpab\Router;

interface DispatcherInterface
{
    public function add($route_alias, $callback);

    public function dispatch($route, $parameters);
}
