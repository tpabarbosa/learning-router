<?php

namespace tpab\Router;

use tpab\Router\Route;

class RouteResolved
{
    public const PATH_NOT_FOUND = 0;
    public const FOUND = 1;
    public const METHOD_NOT_ALLOWED = 2;

    /**
     * Accepts one of the constants defined in class
     *
     * @var integer
     */
    private $status;

    /**
     * Accepts one of the methods defined in class RouteCollection::VERBS
     *
     * @var string
     */
    private $method;

    /**
     * The requested path
     *
     * @var string
     */
    private $path;

    /**
     * The allowed methods
     *
     * @var array
     */
    private $allowed_methods;

        /**
     * The path parameters
     *
     * @var array
     */
    private $path_params;

        /**
     * The callback
     *
     * @var array|string|\Closure|null
     */
    private $callback;

        /**
     * The callback parameters
     *
     * @var array
     */
    private $callback_params;

    /**
     * 
     * @param integer $status
     * @param string $method
     * @param string $path
     * @param Route|null $route
     */
    public function __construct(array $resolved)
    {
        $this->status = $resolved['status'];
        $this->method = $resolved['method'];
        $this->path = $resolved['path'];
        $this->allowed_methods = $resolved['allowed_methods'] ?? [];
        $this->path_params = $resolved['path_params'] ?? [];
        $this->callback = $resolved['callback'] ?? null;
        $this->callback_params = $resolved['callback_params'] ?? [];
    }

    /**
     * Return Route Status
     *
     * @return integer
     */
    public function status()
    {
        return $this->status;
    }

    public function method()
    {
        return $this->method;
    }

    public function path()
    {
        return $this->path;
    }

    public function route()
    {
        return $this->route ? $this->route->path() : null;
    }

    public function callback()
    {
        return $this->callback;
    }

    public function parameters()
    {
        return $this->path_params;
    }

    public function callbackParams()
    {
        return $this->callback_params;
    }

    public function allowedMethods()
    {
        return $this->allowed_methods;
    }

    public function __toString()
    {
        switch ($this->status) {
            case self::PATH_NOT_FOUND:
                return "Path '{$this->path}' was not found.";
                break;
            case self::FOUND:
                return "{$this->path} [{$this->method}]";
                break;
            case self::METHOD_NOT_ALLOWED:
                $allowed = implode(', ', $this->allowedMethods());
                return "Method '{$this->method}' is not allowed to path '{$this->path}'. \r\n Please try one of this methods: [{$allowed}].";
        }
        
    }
}
