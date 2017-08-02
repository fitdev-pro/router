<?php

namespace FitdevPro\FitRouter\Tests\RouteMatchers;

use FitdevPro\FitRouter\Request\IRequest;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;
use FitdevPro\FitRouter\RouteMatchers\MVCDynamicMatcher;
use FitdevPro\FitRouter\TestsLib\FitTest;

class MVCDynamicMatcherTest extends FitTest
{
    public function testMatch()
    {
        $request = $this->prophesize(IRequest::class);
        $collection = $this->prophesize(IRouteCollection::class);

        $request->getRequestMethod()->willReturn('GET');
        $request->getRequsetUrl()->willReturn('/buzz/bar/foo');
        $request->getRequestParams()->willReturn([]);

        $matcher = new MVCDynamicMatcher();
        $route = $matcher->match($collection->reveal(), $request->reveal());

        $this->assertEquals('/buzz/bar/foo', $route->getUrl());
        $this->assertEquals('/buzz/bar/foo', $route->getController());
        $this->assertEquals('/buzz/bar/foo', $route->getAlias());
        $this->assertEquals([
            'requestParams' => [
                'controller' => 'buzz',
                'action' => 'bar',
                'userParams' => ['foo'],
            ],
        ],
            $route->getParameters());
    }

    /**
     * @expectedException \FitdevPro\FitRouter\Exception\MatcherException
     * @expectedExceptionCode 1815130401
     */
    public function testBadMatch()
    {
        $request = $this->prophesize(IRequest::class);
        $collection = $this->prophesize(IRouteCollection::class);

        $request->getRequestMethod()->willReturn('GET');
        $request->getRequsetUrl()->willReturn('/buzz/');

        $matcher = new MVCDynamicMatcher();
        $matcher->match($collection->reveal(), $request->reveal());
    }
}
