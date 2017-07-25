<?php

namespace FitdevPro\FitRouter\Tests\Middleware\Match\After;

use FitdevPro\FitRouter\Middleware\Match\After\MVCData;
use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\TestsLib\FitTest;
use Prophecy\Argument;

class MVCDataTest extends FitTest
{
    private function getEndCallback()
    {
        return function ($data, $output) {
            return $output;
        };
    }

    public function testFill()
    {
        $middleware = new MVCData();

        $routeMock = $this->prophesize(Route::class);
        $routeMock->getController()->willReturn('foo/bar');
        $routeMock->getParameters()->willReturn(['userParams' => []]);
        $routeMock->getUrl()->willReturn('cos/');
        $routeMock->addParameters(Argument::type('array'))
            ->will(function ($args) use (&$newParams) {
                $newParams = $args[0];
            });

        $middleware([], $routeMock->reveal(), $this->getEndCallback());

        $this->assertEquals(['controller' => 'foo', 'action' => 'bar', 'params' => []], $newParams);
    }

    public function testFillWithParams()
    {
        $middleware = new MVCData();

        $routeMock = $this->prophesize(Route::class);
        $routeMock->getController()->willReturn('foo/bar');
        $routeMock->getParameters()->willReturn(['userParams' => ['13', 'xxx']]);
        $routeMock->getUrl()->willReturn('/cos/:id/:name');
        $routeMock->addParameters(Argument::type('array'))
            ->will(function ($args) use (&$newParams) {
                $newParams = $args[0];
            });

        $middleware([], $routeMock->reveal(), $this->getEndCallback());

        $this->assertEquals(['controller' => 'foo', 'action' => 'bar', 'params' => ['id' => 13, 'name' => 'xxx']],
            $newParams);
    }

    /**
     * @expectedException \FitdevPro\FitRouter\Exception\MiddlewareException
     * @expectedExceptionCode 1815130601
     */
    public function testFillWithTooFewParams()
    {
        $middleware = new MVCData();

        $routeMock = $this->prophesize(Route::class);
        $routeMock->getController()->willReturn('foo/bar');
        $routeMock->getParameters()->willReturn(['userParams' => ['13']]);
        $routeMock->getUrl()->willReturn('/cos/:id/:name');
        $routeMock->addParameters(Argument::type('array'))
            ->will(function ($args) use (&$newParams) {
                $newParams = $args[0];
            });

        $middleware([], $routeMock->reveal(), $this->getEndCallback());
    }

    /**
     * @expectedException \FitdevPro\FitRouter\Exception\MiddlewareException
     * @expectedExceptionCode 1815130602
     */
    public function testFillWithNoParams()
    {
        $middleware = new MVCData();

        $routeMock = $this->prophesize(Route::class);
        $routeMock->getController()->willReturn('foo/bar');
        $routeMock->getParameters()->willReturn([]);
        $routeMock->getUrl()->willReturn('/cos/:id/:name');
        $routeMock->addParameters(Argument::type('array'))
            ->will(function ($args) use (&$newParams) {
                $newParams = $args[0];
            });

        $middleware([], $routeMock->reveal(), $this->getEndCallback());
    }

    /**
     * @expectedException \FitdevPro\FitRouter\Exception\MiddlewareException
     * @expectedExceptionCode 1815130603
     */
    public function testFillWithBadController()
    {
        $middleware = new MVCData();

        $routeMock = $this->prophesize(Route::class);
        $routeMock->getController()->willReturn('foo');
        $routeMock->getParameters()->willReturn([]);
        $routeMock->getUrl()->willReturn('/cos/:id/:name');
        $routeMock->addParameters(Argument::type('array'))
            ->will(function ($args) use (&$newParams) {
                $newParams = $args[0];
            });

        $middleware([], $routeMock->reveal(), $this->getEndCallback());
    }
}
