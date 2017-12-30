<?php

namespace FitdevPro\FitRouter\Middleware\Match\After;

use Assert\Assertion;
use Assert\InvalidArgumentException;
use FitdevPro\FitRouter\Exception\MiddlewareException;
use FitdevPro\FitRouter\Middleware\Match\IAfterMatchMiddleware;
use FitdevPro\FitRouter\Route;

class MVCData implements IAfterMatchMiddleware
{
    protected $segments = 2;

    public function __invoke($data, Route $route, callable $next)
    {
        try {
            $path = $this->extractControllerInfo($route);

            $params = $route->getParameters();
            $params['requestParams']['controller'] = array_shift($path);
            $params['requestParams']['action'] = array_shift($path);
            $params['requestParams']['actionParams'] = $this->extractParamsValues($route);

            $route->addParameters($params);
        } catch (InvalidArgumentException $e) {
            throw new MiddlewareException($e->getMessage(), 0, $e);
        }

        $route = $next($data, $route);

        return $route;
    }

    protected function extractControllerInfo(Route $route): array
    {
        $elements = explode('/', trim($route->getController(), '/'));

        Assertion::greaterOrEqualThan(count($elements), $this->segments,
            'Controller definition has %s segments, should contains at least %s segments.');

        return $elements;
    }

    protected function extractParamsValues(Route $route)
    {
        $paramsOut = array();
        $params = $route->getParameters();

        if (!isset($params['requestParams']['userParams'])) {
            throw new MiddlewareException('Undefinded userParams. Add passed params to route using method addParams() on kay "userParams". ');
        }

        $userParams = $params['requestParams']['userParams'];

        if (preg_match_all('/:([\w-]+)/', $route->getUrl(), $urlParams)) {
            // grab array with matches
            $urlParams = $urlParams[1];

            if (count($urlParams) > count($userParams)) {
                throw new MiddlewareException('Too few parameters.');
            }

            foreach ($urlParams as $key => $name) {
                if (isset($userParams[$key])) {
                    $paramsOut[$name] = $userParams[$key];
                }
            }
        }

        return $paramsOut;
    }
}
