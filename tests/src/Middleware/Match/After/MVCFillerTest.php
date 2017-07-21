<?php

namespace FitdevPro\FitRouter\Tests\RouteFillers;

use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteFillers\MVCFiller;
use FitdevPro\FitRouter\TestsLib\FitTest;
use Prophecy\Argument;

class MVCFillerTest extends FitTest
{
    public function testFill()
    {
        $filler = new MVCFiller();
        $routeMock = $this->prophesize(Route::class);
        $routeMock->getController()->willReturn('foo/bar');
        $routeMock->getParameters()->willReturn(['userParams' => []]);
        $routeMock->getUrl()->willReturn('cos/');
        $routeMock->addParameters(Argument::type('array'))
            ->will(function ($args) use (&$newParams) {
                $newParams = $args[0];
            });

        /** @var Route $route */
        $route = $routeMock->reveal();
        $filler->fill($route);

        $this->assertEquals(['controller' => 'foo', 'action' => 'bar', 'params' => []], $newParams);
    }

    public function testFillWithParams()
    {
        $filler = new MVCFiller();
        $routeMock = $this->prophesize(Route::class);
        $routeMock->getController()->willReturn('foo/bar');
        $routeMock->getParameters()->willReturn(['userParams' => ['13', 'xxx']]);
        $routeMock->getUrl()->willReturn('/cos/:id/:name');
        $routeMock->addParameters(Argument::type('array'))
            ->will(function ($args) use (&$newParams) {
                $newParams = $args[0];
            });

        /** @var Route $route */
        $route = $routeMock->reveal();
        $filler->fill($route);

        $this->assertEquals(['controller' => 'foo', 'action' => 'bar', 'params' => ['id' => 13, 'name' => 'xxx']],
            $newParams);
    }

    /**
     * @expectedException \FitdevPro\FitRouter\Exception\RouteFillerException
     * @expectedExceptionCode 1815130601
     */
    public function testFillWithTooFewParams()
    {
        $filler = new MVCFiller();
        $routeMock = $this->prophesize(Route::class);
        $routeMock->getController()->willReturn('foo/bar');
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
     * @expectedExceptionCode 1815130602
     */
    public function testFillWithNoParams()
    {
        $filler = new MVCFiller();
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

    /**
     * @expectedException \FitdevPro\FitRouter\Exception\RouteFillerException
     * @expectedExceptionCode 1815130603
     */
    public function testFillWithBadController()
    {
        $filler = new MVCFiller();
        $routeMock = $this->prophesize(Route::class);
        $routeMock->getController()->willReturn('foo');
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
