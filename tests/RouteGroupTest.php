<?php

namespace tpab\Router\Tests;

use tpab\Router\RouteGroup;
use tpab\Router\RouteResolved;
use PHPUnit\Framework\TestCase;

final class RouteGroupTest extends TestCase
{
    public function testIsInstaceOfRouteGroup()
    {
        $group = new RouteGroup('');
        $this->assertInstanceOf(RouteGroup::class, $group);
        $group = new RouteGroup('/group');
        $this->assertInstanceOf(RouteGroup::class, $group);
    }

    public function testCanDefineGroupPath()
    {
        $group = new RouteGroup('');
        $this->assertEquals('', $group->path());
        $group2 = new RouteGroup('/test');
        $this->assertEquals('/test', $group2->path());

        $group3 = new RouteGroup('/group');
        $this->assertEquals('/group', $group3->path());
    }

    public function testCanAddSubGroup()
    {
        $group = new RouteGroup('');
        $subgroup = $group->group('/test');
        $this->assertEquals('/test', $subgroup->path());

        $group2 = new RouteGroup('/group');
        $subgroup2 = $group2->group('/test');
        $this->assertEquals('/group/test', $subgroup2->path());
    }

    public function testCanAddNewSubGroups()
    {
        $group = new RouteGroup('');
        $subgroup = $group->group('/test');
        $subgroup->add('get', '/getTest', 'Get Test');
        $subgroup2 = $group->group('/test2');
        $subgroup2->add('get', '/getTest', 'Get Test');
        $this->assertNotEquals($subgroup2, $subgroup);
        $this->assertTrue($subgroup2->hasRoute('/test2/getTest'));
        $this->assertFalse($subgroup2->hasRoute('/test/getTest'));
        $this->assertFalse($subgroup->hasRoute('/test2/getTest'));
        $this->assertTrue($subgroup->hasRoute('/test/getTest'));
    }

    public function testCanNotAddSubGroupTwice()
    {
        $group = new RouteGroup('');
        $subgroup = $group->group('/test');
        $subgroup->add('get', '/getTest', 'Get Test');
        $subgroup2 = $group->group('/test');
        $subgroup2->add('get', '/getTest2', 'Get Test');
        $this->assertEquals($subgroup2, $subgroup);
        $this->assertTrue($subgroup2->hasRoute('/test/getTest'));
        $this->assertTrue($subgroup->hasRoute('/test/getTest2'));

        $group = new RouteGroup('/group');
        $subgroup = $group->group('/test');
        $subgroup->add('get', '/getTest', 'Get Test');
        $subgroup2 = $group->group('/test');
        $subgroup2->add('get', '/getTest2', 'Get Test');
        $this->assertEquals($subgroup2, $subgroup);
        $this->assertTrue($subgroup2->hasRoute('/group/test/getTest'));
        $this->assertTrue($subgroup->hasRoute('/group/test/getTest2'));
    }

    public function testCanAddRouteToGroup()
    {
        $group = new RouteGroup('');
        $group->add('get', '/getTest', 'Get Test');
        $this->assertTrue($group->hasRoute('/getTest'));
        $group2 = new RouteGroup('/test');
        $group2->add('get', '/getTest', 'Get Test');
        $this->assertTrue($group2->hasRoute('/test/getTest'));
    }

    public function testCanResolveRoute()
    {
        $group = new RouteGroup('');
        $group->add('get', '/getTest', 'Get Test');
        $route = $group->resolve('get', '/getTest');
        $this->assertInstanceOf(RouteResolved::class, $route);
        $this->assertEquals('Get Test', $route->callback());
        $group2 = new RouteGroup('/test');
        $group2->add('get', '/getTest', 'Test Get Test');
        $route2 = $group2->resolve('get', '/test/getTest');
        $this->assertInstanceOf(RouteResolved::class, $route2);
        $this->assertEquals('Test Get Test', $route2->callback());
    }

    public function testCanResolveSubgroupRoute()
    {
        $group = new RouteGroup('/group');
        $subgroup = $group->group('/subgroup');
        $subgroup->add('get', '/getTest', 'Subgroup Get Test');
        $route = $group->resolve('get', '/group/subgroup/getTest');
        $this->assertInstanceOf(RouteResolved::class, $route);
        $this->assertEquals('Subgroup Get Test', $route->callback());
    }
}
