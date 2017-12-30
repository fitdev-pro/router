<?php

namespace FitdevPro\FitRouter\Middleware\Match\After;

use Assert\InvalidArgumentException;
use FitdevPro\FitRouter\Exception\MiddlewareException;
use FitdevPro\FitRouter\Route;

class HMVCData extends MVCData
{
    protected $segments = 3;


    public function __invoke($data, Route $route, callable $next)
    {
        try {
            $path = $this->extractControllerInfo($route);

            $params = $route->getParameters();
            $params['requestParams']['module'] = array_shift($path);
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
}
