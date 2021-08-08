<?php

namespace tpab\Router\Demo;

use DI\Container;
use tpab\Router\DispatcherInterface;

class DIContainerDispatcher implements DispatcherInterface
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

    public function dispatch($route, $parameters)
    {
        $to_return = $this->container->get($route);

        if (is_callable($to_return)) {
            if (is_array($to_return)) {
                $to_return[0] = $this->container->make($to_return[0], $parameters);
            }
            var_dump($to_return);
            $to_return = $this->container->call($to_return, $parameters);
        }

        return $to_return;
    }
}
