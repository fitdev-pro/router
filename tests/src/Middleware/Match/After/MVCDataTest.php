<?php

namespace FitdevPro\FitRouter\Tests\Middleware\Match\After;

use FitdevPro\FitRouter\Middleware\Match\After\MVCData;
use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\TestsLib\FitTest;
use Prophecy\Argument;

class MVCDataTest extends FitTest
{
    public function testFill()
    {
        $middleware = new MVCData();

        $routeMock = $this->prophesize(Route::class);
        $routeMock->getController()->willReturn('foo/bar');
        $routeMock->getParameters()->willReturn(['requestParams' => ['userParams' => []]]);
        $routeMock->getUrl()->willReturn('cos/');
        $routeMock->addParameters(Argument::type('array'))
            ->will(function ($args) use (&$newParams) {
                $newParams = $args[0];
            });

        $middleware([], $routeMock->reveal(), $this->getEndCallback());

        $this->assertEquals([
            'requestParams' => [
                'controller' => 'foo',
                'action' => 'bar',
                'userParams' => [],
                'actionParams' => []
            ]
        ],
            $newParams);
    }

    private function getEndCallback()
    {
        return function ($data, $output) {
            return $output;
        };
    }

    public function testFillWithParams()
    {
        $middleware = new MVCData();

        $routeMock = $this->prophesize(Route::class);
        $routeMock->getController()->willReturn('foo/bar');
        $routeMock->getParameters()->willReturn(['requestParams' => ['userParams' => ['13', 'xxx']]]);
        $routeMock->getUrl()->willReturn('/cos/:id/:name');
        $routeMock->addParameters(Argument::type('array'))
            ->will(function ($args) use (&$newParams) {
                $newParams = $args[0];
            });

        $middleware([], $routeMock->reveal(), $this->getEndCallback());

        $this->assertEquals([
            'requestParams' => [
                'controller' => 'foo',
                'action' => 'bar',
                'actionParams' => ['id' => 13, 'name' => 'xxx'],
                'userParams' => [13, 'xxx'],
            ]
        ],
            $newParams);
    }

    /**
     * @expectedException \FitdevPro\FitRouter\Exception\MiddlewareException
     */
    public function testFillWithTooFewParams()
    {
        $middleware = new MVCData();

        $routeMock = $this->prophesize(Route::class);
        $routeMock->getController()->willReturn('foo/bar');
        $routeMock->getParameters()->willReturn(['requestParams' => ['userParams' => ['13']]]);
        $routeMock->getUrl()->willReturn('/cos/:id/:name');
        $routeMock->addParameters(Argument::type('array'))
            ->will(function ($args) use (&$newParams) {
                $newParams = $args[0];
            });

        $middleware([], $routeMock->reveal(), $this->getEndCallback());
    }

    /**
     * @expectedException \FitdevPro\FitRouter\Exception\MiddlewareException
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
