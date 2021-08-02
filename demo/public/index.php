<?php

require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/../app/Utils.php');

//use DI\Container;
use tpab\Router\Router;
use tpab\Router\Demo\ControllerExample;
//use tpab\Router\Demo\DIContainerDispatcher;

// $container = new DI\Container();
// $container->set(ControllerExample::class, function ($teste) {
//     return new ControllerExample($teste);
// });
// $dispatcher = new DIContainerDispatcher($container);

Router::init();

Router::get('/', 'Main Page');

Router::get('/test', 'Test Page');
Router::get('/closure', function ($teste) {
    return 'Testing Closure ' . $teste ;
}, ['teste' => 'My Test']);
Router::get('/closure/{id}', function ($id, $teste) {
    return 'Testing Closure ' . $teste. ' ' . $id;
}, ['teste' => 'My Test']);

Router::get('/controller', [ControllerExample::class, 'index']);
Router::get('/controller/{id:[\d]+}', [ControllerExample::class, 'test'], ['teste' => 'My Test']);

Router::get('/controller/{id:[\d]+}/{b}/{value:[([:alpha:]:_)]+}', [ControllerExample::class, 'test']);
Router::post('/post', 'Testing Post');
Router::add('PATCH', '/post', 'Testing Patch');
Router::add(['PATCH', 'delete'], '/test', 'Testing array methods');

Router::group('/group')
    ->add('get', '/', 'First testing group')
    ->add('get', '/new', 'Second testing group');

$route_resolved = Router::resolve(method(), path());

echo nl2br($route_resolved);
//echo $router->dispatch(method(), path());
