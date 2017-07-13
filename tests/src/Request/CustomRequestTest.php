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
}
