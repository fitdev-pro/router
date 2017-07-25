<?php

namespace FitdevPro\FitRouter\Middleware\Generate;

use FitdevPro\FitRouter\Middleware\IRouterMiddleware;

interface IBeforeGenerateMiddleware extends IRouterMiddleware
{
    public function __invoke(array $params, string $routeController, callable $next);
}
