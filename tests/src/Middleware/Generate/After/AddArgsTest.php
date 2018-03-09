<?php

namespace FitdevPro\FitRouter\Tests\Middleware\Generate\After;

use FitdevPro\FitRouter\Middleware\Generate\After\AddArgs;
use FitdevPro\FitRouter\TestsLib\FitTest;

class AddArgsTest extends FitTest
{
    private function getEndCallback()
    {
        return function ($data, $output) {
            return $output;
        };
    }

    public function testFillNoParams()
    {
        $urlMiddleware = new AddArgs();

        $output = $urlMiddleware(['params' => []], 'test', $this->getEndCallback());

        $this->assertEquals('test', $output);
    }

    public function testFill()
    {
        $urlMiddleware = new AddArgs();

        $output = $urlMiddleware(['params' => ['name' => 'bazz']], 'test/:name/bar', $this->getEndCallback());

        $this->assertEquals('test/bazz/bar', $output);
    }

    /**
     * @expectedException \FitdevPro\FitRouter\Exception\MiddlewareException
     * @expectedExceptionCode 1815010601
     */
    public function testFillTooFewArgs()
    {
        $urlMiddleware = new AddArgs();

        $urlMiddleware(['params' => ['name' => 'bazz']], 'test/:name/:bar', $this->getEndCallback());
    }

    /**
     * @expectedException \FitdevPro\FitRouter\Exception\MiddlewareException
     * @expectedExceptionCode 1815010602
     */
    public function testFillBadArgs()
    {
        $urlMiddleware = new AddArgs();

        $urlMiddleware(['params' => ['name' => 'bazz', 'di' => 1]], 'test/:name/:id', $this->getEndCallback());
    }
}
