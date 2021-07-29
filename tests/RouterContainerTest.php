<?php

namespace Tpab\Router\Tests;

use Tpab\Router\Router;
use PHPUnit\Framework\TestCase;

final class RouterContainerTest extends TestCase
{
    public function testIsInstaceOfRouter()
    {
        $router = new Router('get', '/');
        $this->assertInstanceOf(Router::class, $router);
    }

    public function testCanAddGetRoute()
    {
        $router = new Router('get', '/');
        $router->get('/getTest', 'Test');
        $this->assertTrue($router->hasRoute('get', '/getTest'));
    }

    public function testCanAddPostRoute()
    {
        $router = new Router('post', '/');
        $router->post('/postTest', 'Test');
        $this->assertTrue($router->hasRoute('post', '/postTest'));
    }

    public function testCanResolveStringRoute()
    {
        $router = new Router('get', '/test');
        $router->get('/test', 'Test');
        $route = $router->resolve();
        $this->assertEquals('Test', $route);
    }

    public function testCanResolveClosureRoute()
    {
        $router = new Router('get', '/test');
        $router->get('/test', function () {
            return 'Test';
        });
        $route = $router->resolve();
        $this->assertEquals('Test', $route);
    }

    public function testCanResolveControllerRoute()
    {
        $router = new Router('get', '/test');
        $router->get('/test', [ControllerMock::class, 'index']);
        $route = $router->resolve();
        $this->assertEquals('Testing Controller', $route);
    }

    public function testCanResolveRouteClousureWithParameter()
    {
        $router = new Router('get', '/test/123');
        $router->get('/test/{:id}', function ($id) {
            return 'Test Id: ' . $id;
        });
        $route = $router->resolve();
        $this->assertEquals('Test Id: 123', $route);
    }

    public function testCanResolveRouteControllerWithParameter()
    {
        $router = new Router('get', '/test/123');
        $router->get('/test/{:id}', [ControllerMock::class, 'index']);
        $route = $router->resolve();
        $this->assertEquals('Testing Controller Id: 123', $route);
    }

    public function testCanResolveRouteWithParameters()
    {
        $router = new Router('get', '/test/123');
        $router->get('/test/{:id}', [ControllerMock::class, 'index']);
        $router->get('/test/{:id}/{:action}', function ($id, $action='') {
            return 'Test Id: ' . $id . PHP_EOL . 'Action: ' . $action;
        });

        $route = $router->resolve();
        $this->assertEquals('Testing Controller Id: 123', $route);
    }

    public function testCanResolveRouteWithParametersNewTest()
    {
        $router = new Router('get', '/test/123/edit');
        $router->get('/test/{:id}', [ControllerMock::class, 'index']);
        $router->get('/test/{:id}/{:action}', function ($id, $action) {
            return 'Test Id: ' . $id . PHP_EOL . 'Action: ' . $action;
        });
        $route = $router->resolve();
        $this->assertEquals('Test Id: 123' . PHP_EOL . 'Action: edit', $route);
    }

    public function testCanReturnPageNotFound()
    {
        $router = new Router('get', '/test');
        $router->get('/', 'Test');
        $route = $router->resolve();
        $this->assertEquals('Page Not Found', $route);
    }
}

class ControllerMock
{
    public function index($id = null)
    {
        return $id ? 'Testing Controller Id: ' . $id : 'Testing Controller';
    }
}
