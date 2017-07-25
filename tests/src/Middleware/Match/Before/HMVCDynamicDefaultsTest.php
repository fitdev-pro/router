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
        $request->setRequsetUrl('/index/index/index')->shouldBeCalled();

        $midleware = new HMVCDynamicDefaults('index', 'index', 'index');

        $midleware([], $request->reveal(), $this->getEndCallback());
    }

    public function testMiddlewareNoAction()
    {
        $request = $this->prophesize(IRequest::class);
        $request->getRequsetUrl()->willReturn('test');
        $request->setRequsetUrl('/test/index/index')->shouldBeCalled();

        $midleware = new HMVCDynamicDefaults('index', 'index', 'index');

        $midleware([], $request->reveal(), $this->getEndCallback());
    }

    public function testMiddlewareNoControllerAndAction()
    {
        $request = $this->prophesize(IRequest::class);
        $request->getRequsetUrl()->willReturn('test/foo');
        $request->setRequsetUrl('/test/foo/index')->shouldBeCalled();

        $midleware = new HMVCDynamicDefaults('index', 'index', 'index');

        $midleware([], $request->reveal(), $this->getEndCallback());
    }
}
