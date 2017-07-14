<?php

namespace FitdevPro\FitRouter\Tests\UrlFillers;

use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\TestsLib\FitTest;
use FitdevPro\FitRouter\UrlFillers\ArgsFiller;

class ArgsFillerTest extends FitTest
{
    public function testFillNoParams()
    {
        $route = $this->prophesize(Route::class);
        $route->getUrl()->willReturn('test');
        $urlFiller = new ArgsFiller();

        $this->assertEquals('test', $urlFiller->getUrl($route->reveal(), []));
    }

    public function testFill()
    {
        $route = $this->prophesize(Route::class);
        $route->getUrl()->willReturn('test/:name');
        $urlFiller = new ArgsFiller();

        $this->assertEquals('test/bazz', $urlFiller->getUrl($route->reveal(), ['name' => 'bazz']));
    }

    /**
     * @expectedException \FitdevPro\FitRouter\Exception\UrlFillerException
     * @expectedExceptionCode 1815010601
     */
    public function testFillTooFewArgs()
    {
        $route = $this->prophesize(Route::class);
        $route->getUrl()->willReturn('test/:name/:id');
        $urlFiller = new ArgsFiller();

        $this->assertEquals('test/bazz', $urlFiller->getUrl($route->reveal(), ['name' => 'bazz']));
    }

    /**
     * @expectedException \FitdevPro\FitRouter\Exception\UrlFillerException
     * @expectedExceptionCode 1815010602
     */
    public function testFillBadArgs()
    {
        $route = $this->prophesize(Route::class);
        $route->getUrl()->willReturn('test/:name/:id');
        $urlFiller = new ArgsFiller();

        $this->assertEquals('test/bazz', $urlFiller->getUrl($route->reveal(), ['name' => 'bazz', 'di' => 1]));
    }
}
