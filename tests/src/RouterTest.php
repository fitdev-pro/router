<?php

namespace FitdevPro\FitRouter\Tests;

use FitdevPro\FitRouter\Exception\MatcherException;
use FitdevPro\FitRouter\Middleware\Match\IAfterMatchMiddleware;
use FitdevPro\FitRouter\Middleware\Match\IBeforeMatchMiddleware;
use FitdevPro\FitRouter\Request\IRequest;
use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;
use FitdevPro\FitRouter\RouteMatchers\IRouteMatcher;
use FitdevPro\FitRouter\Router;
use FitdevPro\FitRouter\TestsLib\FitTest;
use FitdevPro\FitRouter\UrlGenerator\IUrlGenerator;
use Prophecy\Argument;

class RouterTest extends FitTest
{
    public function testRouterMatch()
    {
        $colection = $this->prophesize(IRouteCollection::class);
        $generator = $this->prophesize(IUrlGenerator::class);
        $matcher = $this->prophesize(IRouteMatcher::class);
        $request = $this->prophesize(IRequest::class);

        $route = $this->prophesize(Route::class);
        $route->getAlias()->willReturn('test_route');

        $matcher->match($colection, $request)->willReturn($route->reveal());

        $router = new Router($colection->reveal(), $matcher->reveal(), $generator->reveal());
        $routOut = $router->match($request->reveal());

        $this->assertEquals('test_route', $routOut->getAlias());
    }

    public function testRouterMatchNotFound()
    {
        $colection = $this->prophesize(IRouteCollection::class);
        $generator = $this->prophesize(IUrlGenerator::class);
        $matcher = $this->prophesize(IRouteMatcher::class);
        $request = $this->prophesize(IRequest::class);

        $matcher->match($colection, $request)->willThrow(new MatcherException('Rout not found.'));

        $router = new Router($colection->reveal(), $matcher->reveal(), $generator->reveal());
        $routOut = $router->match($request->reveal());

        $this->assertNull($routOut);
    }

    public function testRouterMiddlewar()
    {
        $colection = $this->prophesize(IRouteCollection::class);
        $generator = $this->prophesize(IUrlGenerator::class);
        $matcher = $this->prophesize(IRouteMatcher::class);
        $request = $this->prophesize(IRequest::class);
        $before = $this->prophesize(IBeforeMatchMiddleware::class);
        $after = $this->prophesize(IAfterMatchMiddleware::class);

        $route = $this->prophesize(Route::class);

        $before->__invoke(null, $request, Argument::any())->shouldBeCalled()->willReturn($request);
        $after->__invoke(null, $route, Argument::any())->shouldBeCalled()->willReturn($route);

        $matcher->match($colection, $request)->willReturn($route->reveal());

        $router = new Router($colection->reveal(), $matcher->reveal(), $generator->reveal());
        $router->appendMiddleware($after->reveal());
        $router->appendMiddleware($before->reveal());

        $router->match($request->reveal());
    }

    public function testRouterAddRoute()
    {
        $colection = $this->prophesize(IRouteCollection::class);
        $generator = $this->prophesize(IUrlGenerator::class);
        $matcher = $this->prophesize(IRouteMatcher::class);
        $route = $this->prophesize(Route::class);

        $colection->add($route)->shouldBeCalled();

        $router = new Router($colection->reveal(), $matcher->reveal(), $generator->reveal());
        $router->addRoute($route->reveal());
    }

    public function testRouterLoadRoute()
    {
        $colection = $this->prophesize(IRouteCollection::class);
        $generator = $this->prophesize(IUrlGenerator::class);
        $matcher = $this->prophesize(IRouteMatcher::class);

        $colection->load([])->shouldBeCalled();

        $router = new Router($colection->reveal(), $matcher->reveal(), $generator->reveal());
        $router->loadRoutes([]);
    }

    public function testRouterGenerate()
    {
        $colection = $this->prophesize(IRouteCollection::class);
        $matcher = $this->prophesize(IRouteMatcher::class);
        $generator = $this->prophesize(IUrlGenerator::class);
        $generator->setRouteCollection($colection)->shouldBeCalled();

        $router = new Router($colection->reveal(), $matcher->reveal(), $generator->reveal());
        $urlGenerator = $router->getUrlGenerate();

        $this->assertEquals($generator->reveal(), $urlGenerator);
    }
}
