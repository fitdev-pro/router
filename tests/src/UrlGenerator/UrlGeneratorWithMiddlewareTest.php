<?php

namespace FitdevPro\FitRouter\Tests\Middleware\Match\After;

use FitdevPro\FitRouter\Middleware\Generate\IAfterGenerateMiddleware;
use FitdevPro\FitRouter\Middleware\Generate\IBeforeGenerateMiddleware;
use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;
use FitdevPro\FitRouter\TestsLib\FitTest;
use FitdevPro\FitRouter\UrlGenerator\UrlGenerator;
use FitdevPro\FitRouter\UrlGenerator\UrlGeneratorWithMiddleware;
use Prophecy\Argument;

class UrlGeneratorWithMiddlewareTest extends FitTest
{
    public function testGenerateUrl()
    {
        $route = $this->prophesize(Route::class);
        $route->getUrl()->willReturn('/test/url');

        $collection = $this->prophesize(IRouteCollection::class);
        $collection->get('/test/index/index')->willReturn($route->reveal());

        $generator = new UrlGeneratorWithMiddleware();
        $generator->setRouteCollection($collection->reveal());

        $url = $generator->generate('/test/index/index');

        $this->assertEquals('/test/url', $url);
    }

    public function testGenerateUrlWithBeforeMiddleware()
    {
        $route = $this->prophesize(Route::class);
        $route->getUrl()->willReturn('/test/url');

        $collection = $this->prophesize(IRouteCollection::class);
        $collection->get('/pl/test/index/index')->willReturn($route->reveal());

        $middleware = $this->prophesize(IBeforeGenerateMiddleware::class);
        $middleware->__invoke(['lang' => 'pl'], '/test/index/index',
            Argument::any())->shouldBeCalled()->willReturn('/pl/test/index/index');

        $generator = new UrlGeneratorWithMiddleware();
        $generator->setRouteCollection($collection->reveal());
        $generator->appendMiddleware($middleware->reveal());

        $url = $generator->generate('/test/index/index', ['lang' => 'pl']);

        $this->assertEquals('/test/url', $url);
    }

    public function testGenerateUrlWithAfterMiddleware()
    {
        $route = $this->prophesize(Route::class);
        $route->getUrl()->willReturn('/test/url');

        $collection = $this->prophesize(IRouteCollection::class);
        $collection->get('/test/index/index')->willReturn($route->reveal());

        $middleware = $this->prophesize(IAfterGenerateMiddleware::class);
        $middleware->__invoke(['route' => $route, 'params' => ['lang' => 'pl']], '/test/url',
            Argument::any())->shouldBeCalled()->willReturn('/pl/test/url');

        $generator = new UrlGeneratorWithMiddleware();
        $generator->setRouteCollection($collection->reveal());
        $generator->appendMiddleware($middleware->reveal());

        $url = $generator->generate('/test/index/index', ['lang' => 'pl']);

        $this->assertEquals('/pl/test/url', $url);
    }

    public function testGenerateUrlWithBothMiddleware()
    {
        $route = $this->prophesize(Route::class);
        $route->getUrl()->willReturn('/test/url');

        $collection = $this->prophesize(IRouteCollection::class);
        $collection->get('/en/test/index/index')->willReturn($route->reveal());

        $middlewareBefore = $this->prophesize(IBeforeGenerateMiddleware::class);
        $middlewareBefore->__invoke(['lang' => 'en'], '/test/index/index',
            Argument::any())->shouldBeCalled()->willReturn('/en/test/index/index');

        $middlewareAfter = $this->prophesize(IAfterGenerateMiddleware::class);
        $middlewareAfter->__invoke(['route' => $route, 'params' => ['lang' => 'en']], '/test/url',
            Argument::any())->shouldBeCalled()->willReturn('/en/test/url');

        $generator = new UrlGeneratorWithMiddleware();
        $generator->setRouteCollection($collection->reveal());
        $generator->appendMiddleware($middlewareBefore->reveal());
        $generator->appendMiddleware($middlewareAfter->reveal());

        $url = $generator->generate('/test/index/index', ['lang' => 'en']);

        $this->assertEquals('/en/test/url', $url);
    }
}
