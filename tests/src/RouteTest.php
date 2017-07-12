<?php

namespace FitdevPro\FitRouter\Tests;

use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\TestsLib\FitTest;

class RouteTest extends FitTest
{
    public function testRouteUrl()
    {
        $route = new Route('test/', 'test');

        $this->assertEquals('/test', $route->getUrl());
    }

    public function testRouteController()
    {
        $route = new Route('test/', 'testController');

        $this->assertEquals('testController', $route->getController());
    }

    public function testRouteWithName()
    {
        $route = new Route('test', 'test', ['alias' => 'POST']);

        $this->assertEquals('POST', $route->getAlias());
    }

    public function testRouteWithoutName()
    {
        $route = new Route('test', 'test');

        $this->assertEquals('test', $route->getAlias());
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionCode 16
     */
    public function testRouteWithNameException()
    {
        new Route('test', 'test', ['alias' => 1]);
    }

    public function testRouteWithMethod()
    {
        $route = new Route('test', 'test', ['methods' => ['POST']]);

        $this->assertEquals(['POST'], $route->getMethods());
    }

    public function testRouteWithMethodString()
    {
        $route = new Route('test', 'test', ['methods' => 'POST']);

        $this->assertEquals(['POST'], $route->getMethods());
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionCode 24
     */
    public function testRouteWithMethodException()
    {
        $route = new Route('test', 'test', ['methods' => 1]);

        $this->assertEquals(['POST'], $route->getMethods());
    }

    public function testRouteWithParams()
    {
        $route = new Route('test', 'test', ['parameters' => ['test' => true]]);

        $this->assertEquals(['test' => true], $route->getParameters());
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionCode 24
     */
    public function testRouteWithParamsException()
    {
        $route = new Route('test', 'test', ['parameters' => 'test']);

        $this->assertEquals(['test' => true], $route->getParameters());
    }

    public function testRouteAddParams()
    {
        $route = new Route('test', 'test', ['parameters' => ['test' => true]]);

        $route->addParameters(['test2' => 'foo']);

        $this->assertEquals(['test' => true, 'test2' => 'foo'], $route->getParameters());
    }

    public function testRouteWithValidation()
    {
        $route = new Route('test', 'test', ['param-validation' => ['test' => true]]);

        $this->assertEquals(['test' => true], $route->getParamValidation());
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionCode 24
     */
    public function testRouteWithValidationException()
    {
        $route = new Route('test', 'test', ['param-validation' => 'test']);

        $this->assertEquals(['test' => true], $route->getParameters());
    }
}
