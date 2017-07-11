<?php

namespace FitdevPro\FitRouter\RouteMatcher;

use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteCollection;

interface IRouteMatcher
{
    public function match(RouteCollection $routeCollection, string $requestUrl, string $requestMethod): Route;
}
