<?php

namespace LearningApp;

use DI\Container;
use Tpab\Router\IRouterContainer;

class DIContainerAdapter implements IRouterContainer
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function add($route, $callback)
    {
        if ($callback instanceof \Closure) {
            $this->container->set($route, \DI\value($callback));
        } else {
            $this->container->set($route, $callback);
        }
    }

    public function get($route, $parameters)
    {
        $to_return = $this->container->get($route);

        if (is_callable($to_return)) {
            if (is_array($to_return)) {
                $to_return[0] = $this->container->make($to_return[0], $parameters);
            }
            $to_return = $this->container->call($to_return, $parameters);
        }

        return $to_return;
    }
}
