<?php

namespace FitdevPro\FitRouter\Tests;

use FitdevPro\FitRouter\Exception\MatcherException;
use FitdevPro\FitRouter\Request\IRequest;
use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;
use FitdevPro\FitRouter\RouteFillers\IRouteFiller;
use FitdevPro\FitRouter\RouteMatchers\IRouteMatcher;
use FitdevPro\FitRouter\Router;
use FitdevPro\FitRouter\TestsLib\FitTest;
use FitdevPro\FitRouter\UrlFillers\IUrlFiller;

class RouterTest extends FitTest
{
    public function testRouterMatch()
    {
        $colection = $this->prophesize(IRouteCollection::class);
        $matcher = $this->prophesize(IRouteMatcher::class);
        $request = $this->prophesize(IRequest::class);

        $route = $this->prophesize(Route::class);
        $route->getAlias()->willReturn('test_route');

        $matcher->match($colection, $request)->willReturn($route->reveal());

        $router = new Router($colection->reveal(), $matcher->reveal());
        $routOut = $router->match($request->reveal());

        $this->assertEquals('test_route', $routOut->getAlias());
    }

    public function testRouterMatchNotFound()
    {
        $colection = $this->prophesize(IRouteCollection::class);
        $matcher = $this->prophesize(IRouteMatcher::class);
        $request = $this->prophesize(IRequest::class);

        $matcher->match($colection, $request)->willThrow(new MatcherException('Rout not found.'));

        $router = new Router($colection->reveal(), $matcher->reveal());
        $routOut = $router->match($request->reveal());

        $this->assertNull($routOut);
    }

    public function testRouterFiller()
    {
        $colection = $this->prophesize(IRouteCollection::class);
        $matcher = $this->prophesize(IRouteMatcher::class);
        $request = $this->prophesize(IRequest::class);
        $filler = $this->prophesize(IRouteFiller::class);

        $route = $this->prophesize(Route::class);

        $matcher->match($colection, $request)->willReturn($route->reveal());

        $filler->fill($route)->shouldBeCalled();

        $router = new Router($colection->reveal(), $matcher->reveal(), $filler->reveal());
        $router->match($request->reveal());
    }

    public function testRouterAddRoute()
    {
        $colection = $this->prophesize(IRouteCollection::class);
        $matcher = $this->prophesize(IRouteMatcher::class);
        $route = $this->prophesize(Route::class);

        $colection->add($route)->shouldBeCalled();

        $router = new Router($colection->reveal(), $matcher->reveal());
        $router->addRoute($route->reveal());
    }

    public function testRouterLoadRoute()
    {
        $colection = $this->prophesize(IRouteCollection::class);
        $matcher = $this->prophesize(IRouteMatcher::class);

        $colection->load([])->shouldBeCalled();

        $router = new Router($colection->reveal(), $matcher->reveal());
        $router->loadRoutes([]);
    }

    public function testRouterGenerate()
    {
        $colection = $this->prophesize(IRouteCollection::class);
        $matcher = $this->prophesize(IRouteMatcher::class);
        $route = $this->prophesize(Route::class);

        $controller = 'foo/bar';
        $colection->get($controller)->willReturn($route->reveal());

        $route->getUrl()->willReturn('foo_bar.html');

        $router = new Router($colection->reveal(), $matcher->reveal());
        $url = $router->generate($controller);

        $this->assertEquals('foo_bar.html', $url);
    }

    public function testRouterGenerateWithParams()
    {
        $colection = $this->prophesize(IRouteCollection::class);
        $matcher = $this->prophesize(IRouteMatcher::class);
        $route = $this->prophesize(Route::class);
        $urlFiller = $this->prophesize(IUrlFiller::class);

        $controller = 'foo/bar';
        $colection->get($controller)->willReturn($route->reveal());

        $urlFiller->getUrl($route->reveal(), [])->willReturn('foo_bar.html');

        $router = new Router($colection->reveal(), $matcher->reveal(), null, $urlFiller->reveal());
        $url = $router->generate($controller, []);

        $this->assertEquals('foo_bar.html', $url);
    }
}
