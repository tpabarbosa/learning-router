<?php

namespace Tpab\Router;

interface IRouterContainer
{
    public function add($route, $callback);

    public function get($route, $parameters);
}
