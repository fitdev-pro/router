<?php

namespace FitdevPro\FitRouter\Middleware\Match\After;

use Assert\InvalidArgumentException;
use FitdevPro\FitRouter\Exception\MiddlewareException;
use FitdevPro\FitRouter\Route;

class HMVCData extends MVCData
{
    const
        TOO_FEW_PARAMS = '1815080601',
        NO_USER_PARAMS = '1815080602',
        INVALID_CONTROLLER = '1815080603';

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
            throw new MiddlewareException($e->getMessage(), static::INVALID_CONTROLLER);
        }

        $route = $next($data, $route);

        return $route;
    }
}
