<?php

namespace FitdevPro\FitRouter\RouteMatcher;

use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteCollection;

class HMVCDynamicMatcher extends MVCDynamicMatcher
{
    protected $segments = 3;

    public function match(RouteCollection $routeCollection, string $requestUrl, string $requestMethod): Route
    {
        $route = new Route($requestUrl, ['controller' => $requestUrl]);

        $path = $this->extractUrlInfo($route);

        $out['module'] = array_shift($path);
        $out['controller'] = array_shift($path);
        $out['action'] = array_shift($path);

        $out['attr'] = $this->extractParamsValues($route);

        $route->addParameters($out);

        return $route;
    }
}
