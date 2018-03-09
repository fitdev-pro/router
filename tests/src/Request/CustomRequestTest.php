<?php

namespace FitdevPro\FitRouter\Tests\Request;

use FitdevPro\FitRouter\Request\CustomRequest;
use FitdevPro\FitRouter\TestsLib\FitTest;

class CustomRequestTest extends FitTest
{
    public function testGetRequestMethod()
    {
        $request = new CustomRequest('', 'POST');

        $this->assertEquals('POST', $request->getRequestMethod());
    }

    public function testGetRequsetUrl()
    {
        $request = new CustomRequest('mywebside.com', '');

        $this->assertEquals('mywebside.com', $request->getRequsetUrl());
    }

    public function testGetRequsetParams()
    {
        $request = new CustomRequest('mywebside.com', '');
        $request->addRequestParam('test', 1);

        $this->assertEquals(['test' => 1], $request->getRequestParams());
        $this->assertEquals(1, $request->getRequestParam('test'));
        $this->assertNull($request->getRequestParam('testNull'));
        $this->assertFalse($request->getRequestParam('testNull', false));
    }
}
