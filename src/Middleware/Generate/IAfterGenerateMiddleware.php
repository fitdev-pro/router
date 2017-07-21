<?php

namespace FitdevPro\FitRouter\Middleware\Generate;

use FitdevPro\FitRouter\Middleware\IRouterMiddleware;

interface IAfterGenerateMiddleware extends IRouterMiddleware
{
    public function __invoke(array $data, callable $next);
}
