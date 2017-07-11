<?php

namespace FitdevPro\FitRouter\RouteMatcher;

use FitdevPro\FitRouter\Exception\MatcherException;
use FitdevPro\FitRouter\Request\IRequest;
use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;

class RegexMatcher implements IRouteMatcher
{

    public function match(IRouteCollection $routeCollection, IRequest $request): Route
    {
        foreach ($routeCollection->getAll() as $route) {
            try {
                $this->checkRoute($route, $request->getRequsetUrl(), $request->getRequestMethod());

                return $route;
            } catch (MatcherException $e) {
                continue;
            }
        }

        throw new MatcherException('Rout not found.');
    }

    private function checkRoute(Route $route, $requestUrl, $requestMethod)
    {
        if (!in_array($requestMethod, $route->getMethods(), true)) {
            throw new MatcherException('Method not allowd.');
        }

        $pattern = '@^' . $this->getUrlWithRegex($route) . '/?$@i';

        if (!preg_match($pattern, $requestUrl, $matches)) {
            throw new MatcherException('Route not match.');
        }

        $route->addParameters(['params' => $matches]);
    }

    private function getUrlWithRegex(Route $route)
    {
        return preg_replace_callback(
            '/(:\w+)/',
            function ($matches) use ($route) {
                $regex = $route->getValidation();

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
