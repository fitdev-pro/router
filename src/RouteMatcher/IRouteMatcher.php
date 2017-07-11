<?php

namespace FitdevPro\FitRouter\RouteMatcher;

use FitdevPro\FitRouter\Request\IRequest;
use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;

interface IRouteMatcher
{
    public function match(IRouteCollection $routeCollection, IRequest $request): Route;
}
