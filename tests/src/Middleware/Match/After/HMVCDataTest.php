<?php

namespace FitdevPro\FitRouter\Tests\Middleware\Match\After;

use FitdevPro\FitRouter\Middleware\Match\After\HMVCData;
use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\TestsLib\FitTest;
use Prophecy\Argument;

class HMVCDataTest extends FitTest
{
    public function testFill()
    {
        $middleware = new HMVCData();

        $routeMock = $this->prophesize(Route::class);
        $routeMock->getController()->willReturn('index/foo/bar');
        $routeMock->getParameters()->willReturn(['requestParams' => ['userParams' => []]]);
        $routeMock->getUrl()->willReturn('cos/');
        $routeMock->addParameters(Argument::type('array'))
            ->will(function ($args) use (&$newParams) {
                $newParams = $args[0];
            });

        $middleware([], $routeMock->reveal(), $this->getEndCallback());

        $this->assertEquals([
            'requestParams' => [
                'module' => 'index',
                'controller' => 'foo',
                'action' => 'bar',
                'actionParams' => [],
                'userParams' => [],
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
        $middleware = new HMVCData();

        $routeMock = $this->prophesize(Route::class);
        $routeMock->getController()->willReturn('index/foo/bar');
        $routeMock->getParameters()->willReturn(['requestParams' => ['userParams' => ['13', 'xxx']]]);
        $routeMock->getUrl()->willReturn('/cos/:id/:name');
        $routeMock->addParameters(Argument::type('array'))
            ->will(function ($args) use (&$newParams) {
                $newParams = $args[0];
            });

        $middleware([], $routeMock->reveal(), $this->getEndCallback());

        $this->assertEquals([

            'requestParams' => [
                'module' => 'index',
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
        $middleware = new HMVCData();

        $routeMock = $this->prophesize(Route::class);
        $routeMock->getController()->willReturn('index/foo/bar');
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
        $middleware = new HMVCData();

        $routeMock = $this->prophesize(Route::class);
        $routeMock->getController()->willReturn('index/foo/bar');
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
        $middleware = new HMVCData();

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
}
