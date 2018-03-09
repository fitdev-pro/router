<?php

namespace FitdevPro\FitRouter\Tests\RouteCollection;

use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteCollection\RouteCollection;
use FitdevPro\FitRouter\TestsLib\FitTest;

class RouteCollectionTest extends FitTest
{
    public function testAddNewRoute()
    {
        $collection1 = new RouteCollection();

        $collection1->add(new Route('test', 'test'));
        $collection1->add(new Route('test', 'test', ['alias' => 'test2']));

        $this->assertIsArray($collection1->getAll());
        $this->assertCount(2, $collection1->getAll());
    }

    public function testAddManyRoute()
    {
        $collection1 = new RouteCollection();

        $collection1->load([
            'routeCollection' => [
                'test1' => ['controller' => 'test'],
                'test2' => ['controller' => 'test2'],
                'test3' => ['controller' => 'test2', 'alias' => 'test3']
            ]
        ]);

        $this->assertIsArray($collection1->getAll());
        $this->assertCount(3, $collection1->getAll());
    }

    public function testAddManyGroupsRoute()
    {
        $collection1 = new RouteCollection();

        $collection1->load([
            'routeCollection' => [
                '/test1' => [
                    'group' => [
                        '/a' => [
                            'controller' => 'test1Controller/aAction',
                            'group' => [
                                '/1' => [],
                                '/2' => ['alias' => 'test1.a.2']
                            ],
                        ],
                        '/b' => ['controller' => 'test1/b'],
                    ],
                ],
                '/test2' => ['controller' => 'test2', 'alias' => 'test.2'],
                '/test3' => [
                    'controller' => 'test3Controller',
                    'group' => [
                        '/a' => ['controller' => '/a'],
                        '/b' => ['controller' => '/bAction'],
                    ]
                ]
            ]
        ]);

        $all = $collection1->getAll();
        $this->assertIsArray($all);
        $this->assertCount(6, $all);

        $this->assertArrayHasKey('test1Controller/aAction', $all);
        $this->assertEquals('test1Controller/aAction', $all['test1Controller/aAction']->getController());
        $this->assertEquals('/test1/a/1', $all['test1Controller/aAction']->getUrl());

        $this->assertArrayHasKey('test1.a.2', $all);
        $this->assertEquals('test1Controller/aAction', $all['test1.a.2']->getController());
        $this->assertEquals('/test1/a/2', $all['test1.a.2']->getUrl());

        $this->assertArrayHasKey('test.2', $all);
        $this->assertEquals('test2', $all['test.2']->getController());
        $this->assertEquals('/test2', $all['test.2']->getUrl());

        $this->assertArrayHasKey('test3Controller/bAction', $all);
        $this->assertEquals('test3Controller/bAction', $all['test3Controller/bAction']->getController());
        $this->assertEquals('/test3/b', $all['test3Controller/bAction']->getUrl());
    }

    public function testGetRoute()
    {
        $collection1 = new RouteCollection();

        $collection1->load([
            'routeCollection' => [
                'test1' => ['controller' => 'test'],
                'test2' => ['controller' => 'test2'],
                'test3' => ['controller' => 'test2', 'alias' => 'test3']
            ]
        ]);

        $this->assertInstanceOf(Route::class, $collection1->get('test3'));
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionCode 26
     */
    public function testGetRouteException()
    {
        $collection1 = new RouteCollection();

        $collection1->load([
            'routeCollection' => [
                'test1' => ['controller' => 'test'],
                'test2' => ['controller' => 'test2'],
                'test3' => ['controller' => 'test2', 'alias' => 'test3']
            ]
        ]);

        $this->assertInstanceOf(Route::class, $collection1->get('test3a'));
    }
}
