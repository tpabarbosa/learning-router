<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Learning\Router;
use Learning\ControllerExample;

$router = new Router();

$router->get('/', 'Main Page');
$router->get('/test', 'Test Page');
$router->get('/closure', function () {
    return 'Testing Closure';
});
$router->get('/controller', [ControllerExample::class, 'index']);

$route = $router->resolve(method(), path());

echo $route;



function path()
{
    $path = $_SERVER['REQUEST_URI'] ?? '/';
    $position = strpos($path, '?');
    if ($position === false) {
        return $path;
    }

    return substr($path, 0, $position);
}

function method()
{
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    return strtolower($method);
}


