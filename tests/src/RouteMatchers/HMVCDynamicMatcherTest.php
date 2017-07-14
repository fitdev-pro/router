<?php

namespace FitdevPro\FitRouter\Tests\RouteMatchers;

use FitdevPro\FitRouter\Request\IRequest;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;
use FitdevPro\FitRouter\RouteMatchers\HMVCDynamicMatcher;
use FitdevPro\FitRouter\TestsLib\FitTest;

class HMVCDynamicMatcherTest extends FitTest
{
    public function testMatch()
    {
        $request = $this->prophesize(IRequest::class);
        $collection = $this->prophesize(IRouteCollection::class);

        $request->getRequestMethod()->willReturn('GET');
        $request->getRequsetUrl()->willReturn('/buzz/bar/foo/1');

        $matcher = new HMVCDynamicMatcher();
        $route = $matcher->match($collection->reveal(), $request->reveal());

        $this->assertEquals('/buzz/bar/foo/1', $route->getUrl());
        $this->assertEquals('/buzz/bar/foo/1', $route->getController());
        $this->assertEquals('/buzz/bar/foo/1', $route->getAlias());
        $this->assertEquals(['controller' => 'bar', 'action' => 'foo', 'module' => 'buzz', 'userParams' => [1]],
            $route->getParameters());
    }

    /**
     * @expectedException \FitdevPro\FitRouter\Exception\MatcherException
     * @expectedExceptionCode 1815080401
     */
    public function testBadMatch()
    {
        $request = $this->prophesize(IRequest::class);
        $collection = $this->prophesize(IRouteCollection::class);

        $request->getRequestMethod()->willReturn('GET');
        $request->getRequsetUrl()->willReturn('/buzz/bar');

        $matcher = new HMVCDynamicMatcher();
        $matcher->match($collection->reveal(), $request->reveal());
    }
}
