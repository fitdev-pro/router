<?php

namespace FitdevPro\FitRouter\RouteFillers;

use FitdevPro\FitRouter\Route;

/**
 * Interface IRouteFiller
 * @package FitdevPro\FitRouter\PathParser
 */
interface IRouteFiller
{
    public function fill(Route $route);
}
