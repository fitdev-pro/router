<?php

namespace FitdevPro\FitRouter\RouteMatcher;

use FitdevPro\FitRouter\Request\IRequest;
use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;

class HMVCDynamicMatcher extends MVCDynamicMatcher
{
    protected $segments = 3;

    public function match(IRouteCollection $routeCollection, IRequest $request): Route
    {
        $route = new Route($request->getRequsetUrl(), ['controller' => $request->getRequsetUrl()]);

        $path = $this->extractUrlInfo($route);

        $out['module'] = array_shift($path);
        $out['controller'] = array_shift($path);
        $out['action'] = array_shift($path);

        $out['attr'] = $this->extractParamsValues($route);

        $route->addParameters($out);

        return $route;
    }
}
