<?php

namespace FitdevPro\FitRouter\RouteMatcher;

use FitdevPro\FitRouter\Exception\MatcherException;
use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteCollection;

class RegexMatcher implements IRouteMatcher
{

    public function match(RouteCollection $routeCollection, string $requestUrl, string $requestMethod): Route
    {
        foreach ($routeCollection->getAll() as $route) {
            try {
                $this->checkRoute($route, $requestUrl, $requestMethod);

                return $route;
            } catch (MatcherException $e) {
                continue;
            }
        }

        return null;
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
