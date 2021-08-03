<?php

namespace tpab\Router\Tests;

use tpab\Router\Router;
use tpab\Router\RouteResolved;
use PHPUnit\Framework\TestCase;
use tpab\Router\RouterIsAlredyInitializedException;

final class RouterTest extends TestCase
{
    private $router;


    public function testIsInstaceOfRouter()
    {
        $this->router = Router::init();
        $this->assertInstanceOf(Router::class, $this->router);
    }

    public function testCanNotBeInitiatedTwice()
    {
        $this->expectException(RouterIsAlredyInitializedException::class);
        $this->router = Router::init();
    }

    public function testCanAddStringMethodRoute()
    {
        Router::add('put', '/test', 'Test');
        $this->assertTrue(Router::hasRoute('/test'));
    }

    public function testCanAddArrayMethodsRoute()
    {
        Router::add(['get', 'post'], '/array_methods_test', 'Test');
        $route = Router::resolve('get', '/array_methods_test');
        $this->assertEquals(['GET', 'POST'], $route->allowedMethods());
    }

    public function testCanAppendStringMethodToRoute()
    {
        Router::add('patch', '/append_methods_test', 'Test');
        Router::add('delete', '/append_methods_test', 'Test2');
        $route = Router::resolve('patch', '/append_methods_test');
        $this->assertEquals(['PATCH', 'DELETE'], $route->allowedMethods());
        $this->assertEquals('Test', $route->callback());
        $this->assertEquals('PATCH', $route->method());

        $route = Router::resolve('delete', '/append_methods_test');
        $this->assertEquals(['PATCH', 'DELETE'], $route->allowedMethods());
        $this->assertEquals('Test2', $route->callback());
        $this->assertEquals('DELETE', $route->method());
    }

    public function testCanAppendArrayMethodsToRoute()
    {
        Router::add('patch', '/append_array_methods_test', 'Test', ['var' => 'value']);
        Router::add(['delete', 'options'], '/append_array_methods_test', 'Test2', ['var2' => 'value2']);
        $route = Router::resolve('patch', '/append_array_methods_test');
        $this->assertEquals(['PATCH', 'DELETE', 'OPTIONS'], $route->allowedMethods());
        $this->assertEquals('Test', $route->callback());
        $this->assertEquals(['var' => 'value'], $route->callbackParams('patch'));

        $route = Router::resolve('options', '/append_array_methods_test');
        $this->assertEquals('Test2', $route->callback());
        $this->assertEquals(['var2' => 'value2'], $route->callbackParams('options'));
    }

    public function testCanAddGetRoute()
    {
        Router::get('/getTest', 'Test');
        $this->assertTrue(Router::hasRoute('/getTest'));
    }

    public function testCanAddPostRoute()
    {
        Router::post('/postTest', 'Test');
        $this->assertTrue(Router::hasRoute('/postTest'));
    }

    public function testCanResolveGetRoute()
    {
        $route = Router::resolve('get', '/getTest');
        $this->assertInstanceOf(RouteResolved::class, $route);
        $this->assertEquals('/getTest', $route->path());
        $this->assertEquals('1', $route->status());
        $this->assertEquals('/getTest [GET]', (string) $route);
    }

    public function testCanResolvePostRoute()
    {
        $route = Router::resolve('post', '/postTest');
        $this->assertInstanceOf(RouteResolved::class, $route);
        $this->assertEquals('/postTest', $route->path());
        $this->assertEquals('1', $route->status());
    }

    public function testCanResolveNotFoundRoute()
    {
        $route = Router::resolve('post', '/notFoundTest');
        $this->assertInstanceOf(RouteResolved::class, $route);
        $this->assertEquals('/notFoundTest', $route->path());
        $this->assertEquals('0', $route->status());
        $this->assertEquals("Path '/notFoundTest' was not found.", (string) $route);
    }

    public function testCanResolveMethodNotAllowedRoute()
    {
        $route = Router::resolve('get', '/postTest');
        $this->assertInstanceOf(RouteResolved::class, $route);
        $this->assertEquals('/postTest', $route->path());
        $this->assertEquals('2', $route->status());

        $this->assertEquals("Method 'GET' is not allowed to path '/postTest'. \r\n Please try one of this methods: [POST].", (string) $route);
    }

    public function testCanResolveRouteWithParameters()
    {
        $route = Router::resolve('get', '/getTest');
        $this->assertInstanceOf(RouteResolved::class, $route);
        $this->assertEquals('/getTest', $route->path());
        $this->assertEquals('1', $route->status());
    }

    // public function testCanResolveClosureRoute()
    // {
    //     Router::get('/closureTest', function () {
    //         return 'Closure Test';
    //     });
    //     $route = Router::resolve('get', '/closureTest');
    //     $this->assertEquals('Closure Test', $route->path());
    // }

    // public function testCanResolveControllerRoute()
    // {
    //     Router::get('/controllerTest',[ControllerMock::class, 'index']);
    //     $route = Router::resolve('get', '/controllerTest');
    //     var_dump($route->path());
    //     $this->assertEquals('Testing Controller', $route->path());
    // }

    // public function testCanResolveRouteClousureWithParameter()
    // {
    //     $router = new Router('get', '/test/123');
    //     $router->get('/test/{:id}', function ($id) {
    //         return 'Test Id: ' . $id;
    //     });
    //     $route = $router->resolve();
    //     $this->assertEquals('Test Id: 123', $route);
    // }

    // public function testCanResolveRouteControllerWithParameter()
    // {
    //     $router = new Router('get', '/test/123');
    //     $router->get('/test/{:id}', [ControllerMock::class, 'index']);
    //     $route = $router->resolve();
    //     $this->assertEquals('Testing Controller Id: 123', $route);
    // }

    // public function testCanResolveRouteWithParameters()
    // {
    //     $router = new Router('get', '/test/123');
    //     $router->get('/test/{:id}', [ControllerMock::class, 'index']);
    //     $router->get('/test/{:id}/{:action}', function ($id, $action='') {
    //         return 'Test Id: ' . $id . PHP_EOL . 'Action: ' . $action;
    //     });

    //     $route = $router->resolve();
    //     $this->assertEquals('Testing Controller Id: 123', $route);
    // }

    // public function testCanResolveRouteWithParametersNewTest()
    // {
    //     $router = new Router('get', '/test/123/edit');
    //     $router->get('/test/{:id}', [ControllerMock::class, 'index']);
    //     $router->get('/test/{:id}/{:action}', function ($id, $action) {
    //         return 'Test Id: ' . $id . PHP_EOL . 'Action: ' . $action;
    //     });
    //     $route = $router->resolve();
    //     $this->assertEquals('Test Id: 123' . PHP_EOL . 'Action: edit', $route);
    // }

    // public function testCanReturnPageNotFound()
    // {
    //     $router = new Router('get', '/test');
    //     $router->get('/', 'Test');
    //     $route = $router->resolve();
    //     $this->assertEquals('Page Not Found', $route);
    // }
}

class ControllerMock
{
    public function index($id = null)
    {
        return $id ? 'Testing Controller Id: ' . $id : 'Testing Controller';
    }
}
