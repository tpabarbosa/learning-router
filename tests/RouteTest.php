<?php

namespace tpab\Router\Tests;

use tpab\Router\Route;
use PHPUnit\Framework\TestCase;
use stdClass;

final class RouteTest extends TestCase
{
    public function testIsInstaceOfRouter()
    {
        $route = new Route('get', '/getTest', 'GET Test');
        $this->assertInstanceOf(Route::class, $route);
    }

    public function testRouteWasCreated()
    {
        $route = new Route('get', '/getTest', 'GET Test', ['var' => 'value']);
        $this->assertEquals('/getTest', $route->path());
        $this->assertTrue($route->hasMethod('get'));
        $this->assertEquals('GET Test', $route->callback('get'));
        $this->assertEquals(['var' => 'value'], $route->callbackParams('get'));
    }

    public function testCanAppendStringMethod()
    {
        $route = new Route('get', '/getTest', 'GET Test', ['var' => 'value']);
        $route->addMethods('post', 'Appended POST', ['var2' => 'value2']);

        $this->assertEquals('/getTest', $route->path());
        $this->assertEquals(['GET', 'POST'], $route->methods());

        $this->assertTrue($route->hasMethod('get'));
        $this->assertEquals('GET Test', $route->callback('get'));
        $this->assertEquals(['var' => 'value'], $route->callbackParams('get'));

        $this->assertTrue($route->hasMethod('post'));
        $this->assertEquals('Appended POST', $route->callback('post'));
        $this->assertEquals(['var2' => 'value2'], $route->callbackParams('post'));
    }

    public function testCallbackMustBeStringOrCallable()
    {
        $this->expectException(\Exception::class);
        $route = new Route('get', '/getTest', new stdClass());
    }

    public function testMethodMustBeValidVerb()
    {
        $this->expectException(\Exception::class);
        $route = new Route('other', '/otherTest', 'Test');
    }

    public function testMethodMustBeStringOrArray()
    {
        $this->expectException(\Exception::class);
        $route = new Route(new stdClass(), '/otherTest', 'Test');
    }

    public function testMethodCanNotBeEmptyString()
    {
        $this->expectException(\Exception::class);
        $route = new Route('', '/otherTest', 'Test');
    }

    public function testMethodsCanNotbeEmptyArray()
    {
        $this->expectException(\Exception::class);
        $route = new Route([], '/otherTest', 'Test');
    }

    public function testMethodInArrayMustBeString()
    {
        $this->expectException(\Exception::class);
        $route = new Route([new stdClass()], '/otherTest', 'Test');
    }

    public function testCanNotAppendMethodTwoTimes()
    {
        $route = new Route('get', '/getTest', 'Test');
        $this->expectException(\Exception::class);
        $route->addMethods('get', 'Appended POST', []);
    }

    public function testPathCanNotBeEmptyString()
    {
        $this->expectException(\Exception::class);
        $route = new Route('get', '', 'Test');
    }
}
