<?php

namespace FitdevPro\FitRouter\Tests;

use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteCollection;
use FitdevPro\FitRouter\TestsLib\FitTest;

class RouteCollectionTest extends FitTest
{
    public function testAddNewRoute()
    {
        $collection1 = new RouteCollection();

        $collection1->create('test', ['controller' => 'test']);
        $collection1->add(new Route('test', ['controller' => 'test', 'name' => 'test2']));

        $collection2 = new RouteCollection();

        $collection2->create('test', ['controller' => 'test']);
        $collection2->add(new Route('test', ['controller' => 'test']));

        $this->assertIsArray($collection1->getAll());
        $this->assertCount(2, $collection1->getAll());
        $this->assertCount(1, $collection2->getAll());
    }

    public function testAddManyRoute()
    {
        $collection1 = new RouteCollection();

        $collection1->addMany([
            'routeCollection' => [
                'test1' => ['controller' => 'test'],
                'test2' => ['controller' => 'test2'],
                'test3' => ['controller' => 'test2', 'name' => 'test3']
            ]
        ]);

        $this->assertIsArray($collection1->getAll());
        $this->assertCount(3, $collection1->getAll());
    }

    public function testAddManyGroupsRoute()
    {
        $collection1 = new RouteCollection();

        $collection1->addMany([
            'routeCollection' => [
                '/test1' => [
                    'group' => [
                        '/a' => [
                            'controller' => 'test1Controller/aAction',
                            'group' => [
                                '/1' => [],
                                '/2' => ['name' => 'test1.a.2']
                            ],
                        ],
                        '/b' => ['controller' => 'test1/b'],
                    ],
                ],
                '/test2' => ['controller' => 'test2', 'name' => 'test.2'],
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
}
