<?php

namespace FitdevPro\FitRouter\RouteMatchers;

use FitdevPro\FitRouter\Exception\MatcherException;
use FitdevPro\FitRouter\Exception\MethodNotAllowedException;
use FitdevPro\FitRouter\Exception\NotFoundException;
use FitdevPro\FitRouter\Request\IRequest;
use FitdevPro\FitRouter\Route;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;

class RegexMatcher implements IRouteMatcher
{
    public function match(IRouteCollection $routeCollection, IRequest $request): Route
    {
        /** @var Route $matchedRoute */
        $matchedRoute = null;
        foreach ($routeCollection->getAll() as $route) {
            try {
                $this->checkRoute($route, $request);
                $matchedRoute = $route;
            } catch (MatcherException $e) {
                continue;
            }
        }

        if (is_null($matchedRoute)) {
            throw new NotFoundException();
        }

        if (!in_array($request->getRequestMethod(), $matchedRoute->getMethods(), true)) {
            throw new MethodNotAllowedException();
        }

        return $matchedRoute;
    }

    private function checkRoute(Route $route, IRequest $request)
    {
        $pattern = $this->getUrlWithRegex($route);

        if (!preg_match($pattern, $request->getRequsetUrl(), $matches)) {
            throw new MatcherException('Route not match.');
        }

        array_shift($matches);

        $requestParams = $request->getRequestParams();
        $requestParams['userParams'] = $matches;

        $route->addParameters(['requestParams' => $requestParams]);
    }

    private function getUrlWithRegex(Route $route)
    {
        $callback = function ($matches) use ($route) {
            $regex = $route->getParamValidation();

            $name = ltrim($matches[1], ':');

            if (isset($matches[1], $regex[$name])) {
                return $regex[$name];
            }

            return '([\w-]+)';
        };

        $patern = preg_replace_callback('/(:\w+)/', $callback, $route->getUrl());

        return '@^' . $patern . '/?$@i';
    }
}
