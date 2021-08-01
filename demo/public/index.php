<?php

require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/../app/Utils.php');

use DI\Container;
use tpab\Router\Router;
use tpab\Router\Demo\ControllerExample;
use tpab\Router\Demo\DIContainerDispatcher;

$container = new DI\Container();
$container->set(ControllerExample::class, function ($teste) {
    return new ControllerExample($teste);
});
$dispatcher = new DIContainerDispatcher($container);


$router = new Router();//$dispatcher);//);//

$router->get('/', 'Main Page');
$router->get('/test', 'Test Page');
$router->get('/closure', function ($teste) {
    return 'Testing Closure ' . $teste ;
}, ['teste' => 'My Test']);
$router->get('/closure/{id}', function ($id, $teste) {
    return 'Testing Closure ' . $teste. ' ' . $id;
}, ['teste' => 'My Test']);

$router->get('/controller', [ControllerExample::class, 'index']);
$router->get('/controller/{id:[\d]+}', [ControllerExample::class, 'test'], ['teste' => 'My Test']);

$router->get('/controller/{id:[\d]+}/{b}/{value:[([:alpha:]:_)]+}', [ControllerExample::class, 'test']);
$router->post('/post', 'Testing Post');
$router->add('PATCH', '/post', 'Testing Patch');
$router->add(['PATCH', 'delete'], '/test', 'Testing array methods');

$group = $router->group('/group')
    ->add('get', '/', 'First testing group')
    ->add('get', '/new', 'Second testing group');
//$group->add('get', '/', 'First testing group');
var_dump($group);

$route_resolved = $router->resolve(method(), path());

echo nl2br($route_resolved);
//echo $router->dispatch(method(), path());
