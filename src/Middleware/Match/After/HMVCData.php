<?php

namespace FitdevPro\FitRouter\Middleware\Match\After;

use Assert\InvalidArgumentException;
use FitdevPro\FitRouter\Exception\RouteFillerException;
use FitdevPro\FitRouter\Middleware\AfterMatch\MVCData;
use FitdevPro\FitRouter\Route;

class HMVCData extends MVCData
{
    const
        TOO_FEW_PARAMS = '1815080601',
        NO_USER_PARAMS = '1815080602',
        INVALID_CONTROLLER = '1815080603';

    protected $segments = 3;


    public function __invoke(Route $route, callable $next)
    {
        try {
            $out = [];

            $path = $this->extractControllerInfo($route);

            $out['module'] = array_shift($path);
            $out['controller'] = array_shift($path);
            $out['action'] = array_shift($path);

            $out['params'] = $this->extractParamsValues($route);

            $route->addParameters($out);
        } catch (InvalidArgumentException $e) {
            throw new RouteFillerException($e->getMessage(), static::INVALID_CONTROLLER);
        }

        $route = $next($route);

        return $route;
    }
}