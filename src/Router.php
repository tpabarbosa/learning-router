<?php

namespace tpab\Router;

use tpab\Router\DispatcherInterface;
use tpab\Router\DispatcherNotAssignedException;

class Router
{
    /**
     * The dispatcher
     *
     * @var DispatcherInterface
     */
    private static $dispatcher;

    /**
     * Collection of Route instances
     *
     * @var RouteGroup
     */
    private static $routes;

    /**
     * Router is initialized or not
     *
     * @var boolean
     */
    private static $initialized = false;

    /**
     *
     * @param DispatcherInterface $dispatcher
     */
    private function __construct()
    {
    }

    /**
     * Register a GET route
     *
     * @param string $path
     * @param string|array|\Closure $callback
     * @return void
     */
    public static function get(string $path, $callback, $callback_params = array())
    {
        return self::$routes->add('GET', $path, $callback, $callback_params);
    }

    /**
     * Register a POST route
     *
     * @param string $path
     * @param string|array|\Closure $callback
     * @return void
     */
    public static function post(string $path, $callback, $callback_params = array())
    {
        return self::$routes->add('POST', $path, $callback, $callback_params);
    }

    public static function add($methods, string $path, $callback, array $callback_params = [])
    {
        return self::$routes->add($methods, $path, $callback, $callback_params);
    }

    public static function init(DispatcherInterface $dispatcher = null)
    {
        if (self::$initialized) {
            throw new \Exception('Router is already initialized.');
        }
        self::$initialized = true;
        self::$dispatcher = $dispatcher;
        self::$routes = new RouteGroup('');
    }

    public static function group($group_path)
    {
        return self::$routes->group($group_path);
    }

    public static function resolve($method, $path, DispatcherInterface $dispatcher = null)
    {
        if (! self::$initialized) {
            self::init($dispatcher);
        }
        return self::$routes->resolve($method, $path);
    }

    public static function dispatch(string $method, string $path)
    {
        if (! self::$dispatcher) {
            throw new DispatcherNotAssignedException();
        }

        //$resolved_route = $this->resolve($method, $path);

        return null;//$this->dispatcher->dispatch($resolved_route, 'a');
    }
}
