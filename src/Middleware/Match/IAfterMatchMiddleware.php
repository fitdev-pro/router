<?php

namespace FitdevPro\FitRouter\Middleware\Match;

use FitdevPro\FitRouter\Middleware\IRouterMiddleware;
use FitdevPro\FitRouter\Route;

interface IAfterMatchMiddleware extends IRouterMiddleware
{
    public function __invoke($router, Route $route, callable $next);
}
