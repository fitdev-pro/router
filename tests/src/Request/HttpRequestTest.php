<?php

namespace FitdevPro\FitRouter\Tests\Request;

use FitdevPro\FitRouter\Request\HttpRequest;
use FitdevPro\FitRouter\TestsLib\FitTest;

class HttpRequestTest extends FitTest
{
    public function testGetRequestMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = 'mywebside.com';

        $request = new HttpRequest();

        $this->assertEquals('POST', $request->getRequestMethod());
    }

    public function testGetRequestMethodFromPost()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = 'mywebside.com';
        $_POST['_method'] = 'PUT';

        $request = new HttpRequest();

        $this->assertEquals('PUT', $request->getRequestMethod());
    }

    public function testGetRequestMethodFromPostError()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = 'mywebside.com';
        $_POST['_method'] = 'GET';

        $request = new HttpRequest();

        $this->assertEquals('POST', $request->getRequestMethod());
    }

    public function testGetRequsetUrl()
    {
        $_SERVER['REQUEST_URI'] = 'mywebside.com';

        $request = new HttpRequest();

        $this->assertEquals('mywebside.com', $request->getRequsetUrl());
    }

    public function testGetRequsetUrlWithParams()
    {
        $_SERVER['REQUEST_URI'] = 'mywebside.com?a=1';

        $request = new HttpRequest();

        $this->assertEquals('mywebside.com', $request->getRequsetUrl());
    }
}
