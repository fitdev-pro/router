<?php

namespace FitdevPro\FitRouter\TestsLib;

use PHPUnit\Framework\TestCase;

class FitTest extends TestCase
{
    protected function createConfiguredMock($originalClassName, array $configuration)
    {
        $o = $this->createPartialMock($originalClassName, array_keys($configuration));

        foreach ($configuration as $method => $return) {
            $o->method($method)->willReturn($return);
        }

        return $o;
    }
}
