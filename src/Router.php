<?php

namespace tpab\Router;

use tpab\Router\DispatcherInterface;
use tpab\Router\DispatcherNotAssignedException;

class Router extends RouteGroup
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

    /**
     *
     * @param DispatcherInterface $dispatcher
     */
    public function __construct(DispatcherInterface $dispatcher = null)
    {
        $this->dispatcher = $dispatcher;
        parent::__construct('');
    }

    public function dispatch(string $method, string $path)
    {
        if (! $this->dispatcher) {
            throw new DispatcherNotAssignedException();
        }

        //$resolved_route = $this->resolve($method, $path);

        return null;//$this->dispatcher->dispatch($resolved_route, 'a');
    }
}
