<?php

namespace FitdevPro\FitRouter\UrlGenerator;

use FitdevPro\FitRouter\Middleware\IRouterMiddleware;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;

interface IUrlGenerator
{
    public function appendMiddleware(IRouterMiddleware $middleware);

    public function generate(IRouteCollection $routeCollection, string $routeController, array $params = []): string;
}
