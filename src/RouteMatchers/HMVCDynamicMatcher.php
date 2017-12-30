<?php

namespace FitdevPro\FitRouter\RouteMatchers;

use Assert\InvalidArgumentException;
use FitdevPro\FitRouter\Exception\MatcherException;
use FitdevPro\FitRouter\Request\IRequest;
use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;

class HMVCDynamicMatcher extends MVCDynamicMatcher
{
    protected $segments = 3;

    public function match(IRouteCollection $routeCollection, IRequest $request): Route
    {
        $route = new Route($request->getRequsetUrl(), $request->getRequsetUrl());
        try {
            $path = $this->extractUrlInfo($route);

            $out['requestParams'] = $request->getRequestParams();
            $out['requestParams']['module'] = array_shift($path);
            $out['requestParams']['controller'] = array_shift($path);
            $out['requestParams']['action'] = array_shift($path);
            $out['requestParams']['userParams'] = $this->extractParamsValues($route);

            $route->addParameters($out);

            return $route;
        } catch (InvalidArgumentException $e) {
            throw new MatcherException('Rout not found.');
        }
    }
}
