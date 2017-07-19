<?php

namespace FitdevPro\FitRouter\Middleware\Generate;

use FitdevPro\FitRouter\Middleware\IRouterMiddleware;
use FitdevPro\FitRouter\UrlGenerator;

interface IBeforeGenerateMiddleware extends IRouterMiddleware
{
    public function __invoke(UrlGenerator $route, callable $next);
}
