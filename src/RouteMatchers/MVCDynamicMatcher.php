<?php

namespace FitdevPro\FitRouter\RouteMatchers;

use Assert\Assertion;
use Assert\InvalidArgumentException;
use FitdevPro\FitRouter\Exception\MatcherException;
use FitdevPro\FitRouter\Request\IRequest;
use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;

class MVCDynamicMatcher implements IRouteMatcher
{
    const
        ROUTE_INVALID = '1815130401';

    protected $segments = 2;

    public function match(IRouteCollection $routeCollection, IRequest $request): Route
    {
        $route = new Route($request->getRequsetUrl(), $request->getRequsetUrl());

        try {
            $path = $this->extractUrlInfo($route);

            $out['requestParams'] = $request->getRequestParams();
            $out['requestParams']['controller'] = array_shift($path);
            $out['requestParams']['action'] = array_shift($path);
            $out['requestParams']['userParams'] = $this->extractParamsValues($route);

            $route->addParameters($out);

            return $route;
        } catch (InvalidArgumentException $e) {
            throw new MatcherException('Rout not found.', self::ROUTE_INVALID);
        }
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
            'Controller definition has %s segments, should contains at least %s segments.');

        return $elements;
    }


}
