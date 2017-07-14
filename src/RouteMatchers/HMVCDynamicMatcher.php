<?php

namespace FitdevPro\FitRouter\RouteMatchers;

use Assert\InvalidArgumentException;
use FitdevPro\FitRouter\Exception\MatcherException;
use FitdevPro\FitRouter\Request\IRequest;
use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;

class HMVCDynamicMatcher extends MVCDynamicMatcher
{
    const
        ROUTE_INVALID = '1815080401';

    protected $segments = 3;

    public function match(IRouteCollection $routeCollection, IRequest $request): Route
    {
        $route = new Route($request->getRequsetUrl(), $request->getRequsetUrl());
        try {
            $path = $this->extractUrlInfo($route);

            $out['module'] = array_shift($path);
            $out['controller'] = array_shift($path);
            $out['action'] = array_shift($path);

            $out['userParams'] = $this->extractParamsValues($route);

            $route->addParameters($out);

            return $route;
        } catch (InvalidArgumentException $e) {
            throw new MatcherException('Rout not found.', self::ROUTE_INVALID);
        }
    }
}
