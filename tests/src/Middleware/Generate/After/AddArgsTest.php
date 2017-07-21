<?php

namespace FitdevPro\FitRouter\Tests\UrlFillers;

use FitdevPro\FitRouter\Middleware\Generate\After\AddArgs;
use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\TestsLib\FitTest;

class AddArgsTest extends FitTest
{
    private function getEndCallback()
    {
        return function ($data) {
            return $data;
        };
    }

    public function testFillNoParams()
    {
        $urlMiddleware = new AddArgs();

        $output = $urlMiddleware(['url' => 'test', 'params' => []], $this->getEndCallback());

        $this->assertEquals('test', $output['url']);
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
