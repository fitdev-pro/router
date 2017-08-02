<?php

namespace FitdevPro\FitRouter\RouteMatchers;

use FitdevPro\FitRouter\Exception\MatcherException;
use FitdevPro\FitRouter\Request\IRequest;
use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;

class RegexMatcher implements IRouteMatcher
{
    const
        ROUTE_NOT_FOUND = '1815181301',
        METHOD_NOT_ALLOWD = '1815181302',
        ROUTE_NOT_MATCH = '1815181303';

    public function match(IRouteCollection $routeCollection, IRequest $request): Route
    {
        foreach ($routeCollection->getAll() as $route) {
            try {
                $this->checkRoute($route, $request);

                return $route;
            } catch (MatcherException $e) {
                continue;
            }
        }

        throw new MatcherException('Rout not found.', self::ROUTE_NOT_FOUND);
    }

    private function checkRoute(Route $route, IRequest $request)
    {
        if (!in_array($request->getRequestMethod(), $route->getMethods(), true)) {
            throw new MatcherException('Method not allowd.', self::METHOD_NOT_ALLOWD);
        }

        $pattern = '@^' . $this->getUrlWithRegex($route) . '/?$@i';

        if (!preg_match($pattern, $request->getRequsetUrl(), $matches)) {
            throw new MatcherException('Route not match.', self::ROUTE_NOT_MATCH);
        }

        array_shift($matches);

        $requestParams = $request->getRequestParams();
        $requestParams['userParams'] = $matches;

        $route->addParameters(['requestParams' => $requestParams]);
    }

    private function getUrlWithRegex(Route $route)
    {
        return preg_replace_callback(
            '/(:\w+)/',
            function ($matches) use ($route) {
                $regex = $route->getParamValidation();

                $name = ltrim($matches[1], ':');

                if (isset($matches[1], $regex[$name])) {
                    return $regex[$name];
                }

                return '([\w-]+)';
            },
            $route->getUrl()
        );
    }
}
