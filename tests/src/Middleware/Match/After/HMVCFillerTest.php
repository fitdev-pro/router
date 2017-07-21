<?php

namespace FitdevPro\FitRouter\Tests\RouteFillers;

use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteFillers\HMVCFiller;
use FitdevPro\FitRouter\TestsLib\FitTest;
use Prophecy\Argument;

class HMVCFillerTest extends FitTest
{
    public function testFill()
    {
        $filler = new HMVCFiller();
        $routeMock = $this->prophesize(Route::class);
        $routeMock->getController()->willReturn('index/foo/bar');
        $routeMock->getParameters()->willReturn(['userParams' => []]);
        $routeMock->getUrl()->willReturn('cos/');
        $routeMock->addParameters(Argument::type('array'))
            ->will(function ($args) use (&$newParams) {
                $newParams = $args[0];
            });

        /** @var Route $route */
        $route = $routeMock->reveal();
        $filler->fill($route);

        $this->assertEquals(['module' => 'index', 'controller' => 'foo', 'action' => 'bar', 'params' => []],
            $newParams);
    }

    public function testFillWithParams()
    {
        $filler = new HMVCFiller();
        $routeMock = $this->prophesize(Route::class);
        $routeMock->getController()->willReturn('index/foo/bar');
        $routeMock->getParameters()->willReturn(['userParams' => ['13', 'xxx']]);
        $routeMock->getUrl()->willReturn('/cos/:id/:name');
        $routeMock->addParameters(Argument::type('array'))
            ->will(function ($args) use (&$newParams) {
                $newParams = $args[0];
            });

        /** @var Route $route */
        $route = $routeMock->reveal();
        $filler->fill($route);

        $this->assertEquals([
            'module' => 'index',
            'controller' => 'foo',
            'action' => 'bar',
            'params' => ['id' => 13, 'name' => 'xxx']
        ],
            $newParams);
    }

    /**
     * @expectedException \FitdevPro\FitRouter\Exception\RouteFillerException
     * @expectedExceptionCode 1815080601
     */
    public function testFillWithTooFewParams()
    {
        $filler = new HMVCFiller();
        $routeMock = $this->prophesize(Route::class);
        $routeMock->getController()->willReturn('index/foo/bar');
        $routeMock->getParameters()->willReturn(['userParams' => ['13']]);
        $routeMock->getUrl()->willReturn('/cos/:id/:name');
        $routeMock->addParameters(Argument::type('array'))
            ->will(function ($args) use (&$newParams) {
                $newParams = $args[0];
            });

        /** @var Route $route */
        $route = $routeMock->reveal();
        $filler->fill($route);
    }

    /**
     * @expectedException \FitdevPro\FitRouter\Exception\RouteFillerException
     * @expectedExceptionCode 1815080602
     */
    public function testFillWithNoParams()
    {
        $filler = new HMVCFiller();
        $routeMock = $this->prophesize(Route::class);
        $routeMock->getController()->willReturn('index/foo/bar');
        $routeMock->getParameters()->willReturn([]);
        $routeMock->getUrl()->willReturn('/cos/:id/:name');
        $routeMock->addParameters(Argument::type('array'))
            ->will(function ($args) use (&$newParams) {
                $newParams = $args[0];
            });

        /** @var Route $route */
        $route = $routeMock->reveal();
        $filler->fill($route);
    }

    /**
     * @expectedException \FitdevPro\FitRouter\Exception\RouteFillerException
     * @expectedExceptionCode 1815080603
     */
    public function testFillWithBadController()
    {
        $filler = new HMVCFiller();
        $routeMock = $this->prophesize(Route::class);
        $routeMock->getController()->willReturn('foo/bar');
        $routeMock->getParameters()->willReturn([]);
        $routeMock->getUrl()->willReturn('/cos/:id/:name');
        $routeMock->addParameters(Argument::type('array'))
            ->will(function ($args) use (&$newParams) {
                $newParams = $args[0];
            });

        /** @var Route $route */
        $route = $routeMock->reveal();
        $filler->fill($route);
    }
}
