<?php

namespace FitdevPro\FitRouter\TestsLib;

use PHPUnit\Framework\TestCase;

class FitTest extends TestCase
{
    public function assertIsArray($array, $message = '')
    {
        $this->assertTrue(is_array($array), $message);
    }
}
