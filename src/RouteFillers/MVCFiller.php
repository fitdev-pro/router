<?php

namespace FitdevPro\FitRouter\RouteFillers;

use Assert\Assertion;
use Assert\InvalidArgumentException;
use FitdevPro\FitRouter\Exception\RouteFillerException;
use FitdevPro\FitRouter\Route;

class MVCFiller implements IRouteFiller
{
    const
        TOO_FEW_PARAMS = '1815130601',
        NO_USER_PARAMS = '1815130602',
        INVALID_CONTROLLER = '1815130603';

    protected $segments = 2;

    public function fill(Route $route)
    {
        try {
            $out = [];

            $path = $this->extractControllerInfo($route);
            $out['controller'] = array_shift($path);
            $out['action'] = array_shift($path);

            $out['params'] = $this->extractParamsValues($route);

            $route->addParameters($out);
        } catch (InvalidArgumentException $e) {
            throw new RouteFillerException($e->getMessage(), static::INVALID_CONTROLLER);
        }
    }

    protected function extractParamsValues(Route $route)
    {
        $paramsOut = array();
        $params = $route->getParameters();

        if (!isset($params['userParams'])) {
            throw new RouteFillerException('Undefinded userParams. Add passed params to route using method addParams() on kay "userParams". ',
                static::NO_USER_PARAMS);
        }

        $userParams = $params['userParams'];

        if (preg_match_all('/:([\w-]+)/', $route->getUrl(), $urlParams)) {
            // grab array with matches
            $urlParams = $urlParams[1];

            if (count($urlParams) > count($userParams)) {
                throw new RouteFillerException('Too few parameters.', static::TOO_FEW_PARAMS);
            }

            foreach ($urlParams as $key => $name) {
                if (isset($userParams[$key])) {
                    $paramsOut[$name] = $userParams[$key];
                }
            }
        }

        return $paramsOut;
    }

    protected function extractControllerInfo(Route $route): array
    {
        $elements = explode('/', trim($route->getController(), '/'));

        Assertion::greaterOrEqualThan(count($elements), $this->segments,
            'Controller definition has %s segments, should contains at least %s segments.');

        return $elements;
    }
}
