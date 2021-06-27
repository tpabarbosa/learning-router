<?php

namespace Learning;

class Router
{
    /**
     * The current registered callbacks by routes methods and paths
     * routes[method][path] = callback
     *
     * @var string|array|\Closure[]
     */
    private $routes = array();

    /**
     * The current registered parameters keys by routes methods and paths
     * routes_params[method][path] = [key1, key2]
     *
     * @var array
     */
    private $routes_params = array();

    /**
     * The request method
     * 
     * @var string
     */
    private $method;

    /**
     * The request path
     * 
     * @var string
     */
    private $path;

    public function __construct(string $method, string $path)
    {
        $this->method = $method;
        $this->path = $path;
    }


    /**
     * Register a GET route
     *
     * @param string $path
     * @param string|array|\Closure $callback
     * @return void
     */
    public function get(string $path, $callback)
    {
        $this->add('get', $path, $callback);
    }

    /**
     * Register a POST route
     *
     * @param string $path
     * @param string|array|\Closure $callback
     * @return void
     */
    public function post(string $path, $callback)
    {
        $this->add('post', $path, $callback);
    }

    /**
     * Register a route to router
     *
     * @param string $method get|post
     * @param string $path
     * @param string|array|\Closure $callback
     * @return void
     */
    private function add(string $method, string $path, $callback)
    {
        $this->routes[$method][$path] = $callback;
        $this->routes_params[$method][$path] = $this->setRouteParams($path);
    }

    private function setRouteParams(string $path)
    {
        $params=[];
        preg_match_all('/\/{:\w+}/', $path, $matches);
        if ($matches[0]) {
            foreach ($matches[0] as $param) {
                $params[] = str_replace(['/{:','}'] , '', $param);
            }
        }
        return $params;
    }


    /**
     * Determine if router has a route registered
     *
     * @param string $method
     * @param string $path
     * @return bool
     */
    public function hasRoute(string $method, string $path): bool
    {
        return isset($this->routes[strtolower($method)][$path]);
    }

    /**
     * Returns callback for the route
     *
     * @param string $method
     * @param string $path
     * @return mixed 
     */
    public function resolve()
    {
        $method = $this->method;
        $path = $this->path;
        $params = [];

        if (! $this->hasRoute($method, $path)) {
            $routeParams = $this->checkRouteParams();
            $path = $routeParams['path'] ?? '';
            $params = $routeParams['params'];
        }

        $route = $this->routes[$method][$path] ?? 'Page Not Found';

        if (is_string($route)) {
            return $route;
        }
        
        if (is_array($route)) {
            $route[0] = new $route[0]();
        }
        
        return call_user_func_array($route, $params);

    }

    private function checkRouteParams()
    {
        $method = $this->method;

        foreach ($this->routes[$method] as $route => $callback) {
            preg_match_all('/\/{:\w+}/', $route, $matches);

            if ($matches[0]) {
                $resolvedParams = $this->resolveParams($route, $matches);
                $path = $resolvedParams['path'];

                if ($this->hasRoute($method, $path)) {
                    return $resolvedParams;
                }
            }
        }
    }

    private function resolveParams($route, $matches) 
    {
        $path = $this->path;
        $method = $this->method;

        $exploded_route = explode('/', $route);
        $exploded_path = explode('/', $path);

        if (count($exploded_path) > count($exploded_route)) {
            return [
                'path' => '',
                'params' => []
            ];
        }
        $params = [];
        $param = 0;
        $params_list = $this->routes_params[$method][$route] ?? [];

        foreach ($exploded_path as $key => $value) {
            if (isset($params_list[$param]) && $exploded_path[$key] !== $exploded_route[$key]) {
                $params = array_merge($params, [ $params_list[$param] => $exploded_path[$key]]);
                $param++;
            }
        }

        return [
            'path' => implode('/', $exploded_route),
            'params' => $params
        ];
    }
}
