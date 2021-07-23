<?php

namespace Learning;

interface IRouterContainer
{
    public function add($route, $callback);

    public function get($route, $parameters);
}
