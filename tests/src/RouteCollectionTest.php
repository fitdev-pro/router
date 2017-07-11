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

        $this->assertTrue(is_array($collection1->getAll()));
        $this->assertCount(2, $collection1->getAll());
        $this->assertCount(1, $collection2->getAll());
    }
}
