<?php

namespace FitdevPro\FitRouter\Tests;

use FitdevPro\FitRouter\RouteCollection\RouteCollection;
use FitdevPro\FitRouter\RouteMatcher\RegexMatcher;
use FitdevPro\FitRouter\Router;
use FitdevPro\FitRouter\TestsLib\FitTest;

class RouterTest extends FitTest
{
    public function testRouter()
    {
        $colection = $this->prophesize(RouteCollection::class)->reveal();
        $matcher = $this->prophesize(RegexMatcher::class)->reveal();

        $router = new Router($colection, $matcher);
    }
}
