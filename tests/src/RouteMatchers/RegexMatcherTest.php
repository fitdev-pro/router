<?php

namespace FitdevPro\FitRouter\Tests\RouteMatchers;

use FitdevPro\FitRouter\Request\IRequest;
use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;
use FitdevPro\FitRouter\RouteMatchers\RegexMatcher;
use FitdevPro\FitRouter\TestsLib\FitTest;
use Prophecy\Argument;

class RegexMatcherTest extends FitTest
{
    public function testMatch()
    {
        $request = $this->prophesize(IRequest::class);

        $request->getRequestMethod()->willReturn('GET');
        $request->getRequsetUrl()->willReturn('/buzz/bar/foo');
        $request->getRequestParams()->willReturn([]);

        $newParams = null;

        $matcher = new RegexMatcher();
        $matcher->match($this->getCollection($newParams), $request->reveal());

        $this->assertEquals(['requestParams' => ['userParams' => []]], $newParams);
    }

    private function getCollection(&$newParams)
    {
        $collection = $this->prophesize(IRouteCollection::class);
        $route = $this->prophesize(Route::class);

        $route->getMethods()->willReturn(['GET']);
        $route->getParamValidation()->willReturn(['id' => '([0-9]+)', 'name' => '([a-z]+)']);
        $route->getUrl()->willReturn('/foo/bar/:id/buzz/:name');
        $route->addParameters(Argument::type('array'))
            ->will(function ($args) use (&$newParams) {
                $newParams = $args[0];
            });

        $route2 = $this->prophesize(Route::class);
        $route2->getMethods()->willReturn(['GET']);
        $route2->getParamValidation()->willReturn([]);
        $route2->getUrl()->willReturn('/foo/bar/:id');
        $route2->addParameters(Argument::type('array'))
            ->will(function ($args) use (&$newParams) {
                $newParams = $args[0];
            });

        $route3 = $this->prophesize(Route::class);
        $route3->getMethods()->willReturn(['POST']);
        $route3->getParamValidation()->willReturn(['id' => '([0-9]+)']);
        $route3->getUrl()->willReturn('/save/:id');
        $route3->addParameters(Argument::type('array'))
            ->will(function ($args) use (&$newParams) {
                $newParams = $args[0];
            });

        $route3 = $this->prophesize(Route::class);
        $route3->getMethods()->willReturn(['GET']);
        $route3->getParamValidation()->willReturn([]);
        $route3->getUrl()->willReturn('/buzz/bar/foo');
        $route3->addParameters(Argument::type('array'))
            ->will(function ($args) use (&$newParams) {
                $newParams = $args[0];
            });

        $routesArray = [
            $route->reveal(),
            $route2->reveal(),
            $route3->reveal(),
        ];

        $collection->getAll()->willReturn($routesArray);

        return $collection->reveal();
    }

    public function testMatchParamNoValidation()
    {
        $request = $this->prophesize(IRequest::class);

        $request->getRequestMethod()->willReturn('GET');
        $request->getRequsetUrl()->willReturn('/foo/bar/xxx');
        $request->getRequestParams()->willReturn([]);

        $newParams = null;

        $matcher = new RegexMatcher();
        $matcher->match($this->getCollection($newParams), $request->reveal());

        $this->assertEquals(['requestParams' => ['userParams' => ['xxx']]], $newParams);
    }

    public function testMatchParamWithValidation()
    {
        $request = $this->prophesize(IRequest::class);

        $request->getRequestMethod()->willReturn('GET');
        $request->getRequsetUrl()->willReturn('/foo/bar/5/buzz/xxx');
        $request->getRequestParams()->willReturn([]);

        $newParams = null;

        $matcher = new RegexMatcher();
        $matcher->match($this->getCollection($newParams), $request->reveal());

        $this->assertEquals(['requestParams' => ['userParams' => [5, 'xxx']]], $newParams);
    }

    /**
     * @expectedException \FitdevPro\FitRouter\Exception\NotFoundException
     */
    public function testMatchBadParam()
    {
        $request = $this->prophesize(IRequest::class);

        $request->getRequestMethod()->willReturn('GET');
        $request->getRequsetUrl()->willReturn('/foo/bar/5/buzz/5');
        $request->getRequestParams()->willReturn([]);

        $newParams = null;

        $matcher = new RegexMatcher();
        $matcher->match($this->getCollection($newParams), $request->reveal());
    }

    /**
     * @expectedException \FitdevPro\FitRouter\Exception\MethodNotAllowedException
     */
    public function testMatchBadMethod()
    {
        $request = $this->prophesize(IRequest::class);

        $request->getRequestMethod()->willReturn('PUT');
        $request->getRequsetUrl()->willReturn('/foo/bar/xxx');
        $request->getRequestParams()->willReturn([]);

        $newParams = null;

        $matcher = new RegexMatcher();
        $matcher->match($this->getCollection($newParams), $request->reveal());
    }
}
