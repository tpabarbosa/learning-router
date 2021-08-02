<?php

namespace tpab\Router;

use tpab\Router\DispatcherInterface;
use tpab\Router\DispatcherNotAssignedException;
use tpab\Router\RouterIsAlredyInitializedException;

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

    private static $instance = null;
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
        self::initialize();
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
        self::initialize();
        return self::$routes->add('POST', $path, $callback, $callback_params);
    }

    public static function add($methods, string $path, $callback, array $callback_params = [])
    {
        self::initialize();
        return self::$routes->add($methods, $path, $callback, $callback_params);
    }

    public static function init(DispatcherInterface $dispatcher = null)
    {
        if (self::$initialized) {
            throw new RouterIsAlredyInitializedException('Router is already initialized.');
        }
        self::$initialized = true;
        self::$dispatcher = $dispatcher;
        self::$routes = new RouteGroup('');
        self::$instance = new self();

        return self::$instance;
    }

    public static function group($group_path)
    {
        self::initialize();
        return self::$routes->group($group_path);
    }

    public static function resolve($method, $path, DispatcherInterface $dispatcher = null)
    {
        self::initialize($dispatcher);
        return self::$routes->resolve($method, $path);
    }

    public static function dispatch(string $method, string $path)
    {
        if (! self::$dispatcher) {
            throw new DispatcherNotAssignedException('A Dispatcher is not assigned to Router.');
        }

        //$resolved_route = $this->resolve($method, $path);

        return null;//$this->dispatcher->dispatch($resolved_route, 'a');
    }

    private static function initialize(DispatcherInterface $dispatcher = null)
    {
        if (! self::$initialized) {
            self::init($dispatcher);
        }
    }

    public static function hasRoute($path) 
    {
        return self::$routes->hasRoute($path);
    }
}
