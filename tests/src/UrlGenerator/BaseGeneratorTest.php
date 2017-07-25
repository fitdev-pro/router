<?php

namespace FitdevPro\FitRouter\Tests\Middleware\Match\After;

use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;
use FitdevPro\FitRouter\TestsLib\FitTest;
use FitdevPro\FitRouter\UrlGenerator\BaseGenerator;
use FitdevPro\FitRouter\UrlGenerator\UrlGenerator;

class BaseGeneratorTest extends FitTest
{
    public function testGenerateUrl()
    {
        $route = $this->prophesize(Route::class);
        $route->getUrl()->willReturn('/test/url');

        $collection = $this->prophesize(IRouteCollection::class);
        $collection->get('/test/index/index')->willReturn($route->reveal());

        $generator = new BaseGenerator();
        $generator->setRouteCollection($collection->reveal());

        $url = $generator->generate('/test/index/index');

        $this->assertEquals('/test/url', $url);
    }
}
