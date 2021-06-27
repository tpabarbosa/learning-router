<?php

namespace LearningTests;

use Learning\Router;
use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
    public function testIsInstaceOfRouter()
    {
        $router = new Router();
        $this->assertInstanceOf(Router::class, $router);
    }

    public function testCanAddGetRoute()
    {
        $router = new Router();
        $router->get('/getTest', 'Test');
        $this->assertTrue($router->hasRoute('get', '/getTest'));
    }

    public function testCanAddPostRoute()
    {
        $router = new Router();
        $router->post('/postTest', 'Test');
        $this->assertTrue($router->hasRoute('post', '/postTest'));
    }

    public function testCanResolveRoute()
    {
        $router = new Router();
        $router->get('/test', 'Test');
        $route = $router->resolve('get', '/test');
        $this->assertEquals('Test', $route);
    }

}
