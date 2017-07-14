<?php

namespace FitdevPro\FitRouter\Tests\UrlFillers;

use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\TestsLib\FitTest;
use FitdevPro\FitRouter\UrlFillers\ArgsWithLangFiller;

class ArgsWithLangFillerTest extends FitTest
{
    public function testFillNoParams()
    {
        $route = $this->prophesize(Route::class);
        $route->getUrl()->willReturn('test');
        $urlFiller = new ArgsWithLangFiller('en');

        $this->assertEquals('/en/test', $urlFiller->getUrl($route->reveal(), []));
    }

    public function testFill()
    {
        $route = $this->prophesize(Route::class);
        $route->getUrl()->willReturn('test/:name');
        $urlFiller = new ArgsWithLangFiller('en');

        $this->assertEquals('/en/test/bazz', $urlFiller->getUrl($route->reveal(), ['name' => 'bazz']));
    }

    /**
     * @expectedException \FitdevPro\FitRouter\Exception\UrlFillerException
     * @expectedExceptionCode 1815120601
     */
    public function testFillTooFewArgs()
    {
        $route = $this->prophesize(Route::class);
        $route->getUrl()->willReturn('test/:name/:id');
        $urlFiller = new ArgsWithLangFiller('en');

        $this->assertEquals('test/bazz', $urlFiller->getUrl($route->reveal(), ['name' => 'bazz']));
    }

    /**
     * @expectedException \FitdevPro\FitRouter\Exception\UrlFillerException
     * @expectedExceptionCode 1815120602
     */
    public function testFillBadArgs()
    {
        $route = $this->prophesize(Route::class);
        $route->getUrl()->willReturn('test/:name/:id');
        $urlFiller = new ArgsWithLangFiller('en');

        $this->assertEquals('test/bazz', $urlFiller->getUrl($route->reveal(), ['name' => 'bazz', 'di' => 1]));
    }
}
