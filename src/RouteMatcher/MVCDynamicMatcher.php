<?php

namespace FitdevPro\FitRouter\RouteMatcher;

use Assert\Assertion;
use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteCollection;

class MVCDynamicMatcher implements IRouteMatcher
{
    protected $segments = 2;

    public function match(RouteCollection $routeCollection, string $requestUrl, string $requestMethod): Route
    {
        $route = new Route($requestUrl, ['controller' => $requestUrl]);

        $path = $this->extractUrlInfo($route);

        $out['controller'] = array_shift($path);
        $out['action'] = array_shift($path);

        $out['attr'] = $this->extractParamsValues($route);

        $route->addParameters($out);

        return $route;
    }

    protected function extractParamsValues(Route $route): array
    {
        $elements = explode('/', trim($route->getController(), '/'));
        $attr = array_slice($elements, $this->segments);

        return $attr;
    }

    protected function extractUrlInfo(Route $route): array
    {
        $elements = explode('/', trim($route->getController(), '/'));

        Assertion::greaterOrEqualThan(count($elements), $this->segments,
            'Path has %s segments, should contains at least %s segments.');

        return $elements;
    }


}
