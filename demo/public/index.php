<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

use DI\Container;
use Tpab\Router\Router;
use Tpab\Demo\ControllerExample;
use Tpab\Demo\DIContainerAdapter;

$container = new DI\Container();
// $container->set(ControllerExample::class, function () {
//     return new ControllerExample('With Constructor Parameters');
// });
$container_adapter = new DIContainerAdapter($container);


$router = new Router(method(), path(), $container_adapter);//);//


$router->get('/', 'Main Page');
$router->get('/test', 'Test Page');
$router->get('/closure', function ($teste) {
    return 'Testing Closure ' . $teste ;
}, ['teste' => 'My Test']);
$router->get('/closure/{:id}', function ($id, $teste) {
    return 'Testing Closure ' . $teste. ' ' . $id;
}, ['teste' => 'My Test']);

$router->get('/controller', [ControllerExample::class, 'index']);
$router->get('/controller/{:test}', [ControllerExample::class, 'test'], ['teste' => 'My Test']);



echo $router->resolve();



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
