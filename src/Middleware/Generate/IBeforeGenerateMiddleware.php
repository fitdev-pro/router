<?php

namespace FitdevPro\FitRouter\Middleware\Generate;

use FitdevPro\FitRouter\Middleware\IRouterMiddleware;

interface IBeforeGenerateMiddleware extends IRouterMiddleware
{
    public function __invoke(string $routeController, callable $next);
}
