<?php

namespace FitdevPro\FitRouter\RouteMatchers;

use FitdevPro\FitRouter\Request\IRequest;
use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;

interface IRouteMatcher
{
    public function match(IRouteCollection $routeCollection, IRequest $request): Route;
}
