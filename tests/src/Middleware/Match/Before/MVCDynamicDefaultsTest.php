<?php

namespace FitdevPro\FitRouter\Tests\Middleware\Match\After;

use FitdevPro\FitRouter\Middleware\Match\Before\MVCDynamicDefaults;
use FitdevPro\FitRouter\Request\IRequest;
use FitdevPro\FitRouter\TestsLib\FitTest;

class MVCDynamicDefaultsTest extends FitTest
{
    public function testMiddlewareEmptyUrl()
    {
        $request = $this->prophesize(IRequest::class);
        $request->getRequsetUrl()->willReturn('');
        $request->setRequestUrl('/index/index')->shouldBeCalled();

        $midleware = new MVCDynamicDefaults('index', 'index');

        $midleware([], $request->reveal(), $this->getEndCallback());
    }

    private function getEndCallback()
    {
        return function ($data, $output) {
            return $output;
        };
    }

    public function testMiddlewareNoAction()
    {
        $request = $this->prophesize(IRequest::class);
        $request->getRequsetUrl()->willReturn('test');
        $request->setRequestUrl('/test/index')->shouldBeCalled();

        $midleware = new MVCDynamicDefaults('index', 'index');

        $midleware([], $request->reveal(), $this->getEndCallback());
    }
}
