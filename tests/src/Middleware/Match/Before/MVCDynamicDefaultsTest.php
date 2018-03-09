<?php

namespace FitdevPro\FitRouter\Tests\Middleware\Match\After;

use FitdevPro\FitRouter\Middleware\Match\Before\MVCDynamicDefaults;
use FitdevPro\FitRouter\Request\IRequest;
use FitdevPro\FitRouter\TestsLib\FitTest;

class MVCDynamicDefaultsTest extends FitTest
{
    private function getEndCallback()
    {
        return function ($data, $output) {
            return $output;
        };
    }

    public function testMiddlewareEmptyUrl()
    {
        $request = $this->prophesize(IRequest::class);
        $request->getRequsetUrl()->willReturn('');
        $request->getRequestMethod()->willReturn('POST');
        $request->getRequestParams()->willReturn(['foo'=>'bar']);

        $midleware = new MVCDynamicDefaults('index', 'index');

        $request = $midleware([], $request->reveal(), $this->getEndCallback());

        $this->assertEquals('/index/index', $request->getRequsetUrl());
        $this->assertEquals('POST', $request->getRequestMethod());
        $this->assertEquals(['foo'=>'bar'], $request->getRequestParams());
    }

    public function testMiddlewareNoAction()
    {
        $request = $this->prophesize(IRequest::class);
        $request->getRequsetUrl()->willReturn('test');
        $request->getRequestMethod()->shouldBeCalled();
        $request->getRequestParams()->shouldBeCalled();

        $midleware = new MVCDynamicDefaults('index', 'index');

        $request = $midleware([], $request->reveal(), $this->getEndCallback());

        $this->assertEquals('/test/index', $request->getRequsetUrl());
    }
}
