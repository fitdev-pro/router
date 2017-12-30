<?php

namespace FitdevPro\FitRouter\Middleware\Match;

use FitdevPro\FitRouter\Middleware\IRouterMiddleware;
use FitdevPro\FitRouter\Request\IRequest;

interface IBeforeMatchMiddleware extends IRouterMiddleware
{
    public function __invoke($router, IRequest $request, callable $next);
}
