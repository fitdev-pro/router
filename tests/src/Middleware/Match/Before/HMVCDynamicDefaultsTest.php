<?php

namespace FitdevPro\FitRouter\Tests\Middleware\Match\After;

use FitdevPro\FitRouter\Middleware\Match\Before\HMVCDynamicDefaults;
use FitdevPro\FitRouter\Request\IRequest;
use FitdevPro\FitRouter\TestsLib\FitTest;

class HMVCDynamicDefaultsTest extends FitTest
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

        $midleware = new HMVCDynamicDefaults('index', 'index', 'index');

        $request = $midleware([], $request->reveal(), $this->getEndCallback());

        $this->assertEquals('/index/index/index', $request->getRequsetUrl());
        $this->assertEquals('POST', $request->getRequestMethod());
        $this->assertEquals(['foo'=>'bar'], $request->getRequestParams());
    }

    public function testMiddlewareNoAction()
    {
        $request = $this->prophesize(IRequest::class);
        $request->getRequsetUrl()->willReturn('/test');
        $request->getRequestMethod()->shouldBeCalled();
        $request->getRequestParams()->shouldBeCalled();

        $midleware = new HMVCDynamicDefaults('index', 'index', 'index');

        $request = $midleware([], $request->reveal(), $this->getEndCallback());

        $this->assertEquals('/test/index/index', $request->getRequsetUrl());
    }

    public function testMiddlewareNoControllerAndAction()
    {
        $request = $this->prophesize(IRequest::class);
        $request->getRequsetUrl()->willReturn('/test/foo/');
        $request->getRequestMethod()->shouldBeCalled();
        $request->getRequestParams()->shouldBeCalled();

        $midleware = new HMVCDynamicDefaults('index', 'index', 'index');

        $request = $midleware([], $request->reveal(), $this->getEndCallback());

        $this->assertEquals('/test/foo/index', $request->getRequsetUrl());
    }
}
