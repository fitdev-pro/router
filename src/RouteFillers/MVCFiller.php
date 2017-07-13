<?php

namespace FitdevPro\FitRouter\RouteFillers;

use Assert\Assertion;
use FitdevPro\FitRouter\Exception\RouteFillerException;
use FitdevPro\FitRouter\Route;

class MVCFiller implements IRouteFiller
{
    protected $segments = 2;

    public function fill(Route $route)
    {
        $out = [];

        $path = $this->extractControllerInfo($route);
        $out['controller'] = array_shift($path);
        $out['action'] = array_shift($path);

        $out['params'] = $this->extractParamsValues($route);

        $route->addParameters($out);
    }

    protected function extractParamsValues(Route $route)
    {
        $params = array();
        $userParams = $route->getParameters()['userParams'];

        if (preg_match_all('/:([\w-]+)/', $route->getUrl(), $urlParams)) {
            // grab array with matches
            $urlParams = $urlParams[1];

            if (count($urlParams) > count($userParams)) {
                throw new RouteFillerException('Too few parameters.');
            }

            foreach ($urlParams as $key => $name) {
                if (isset($userParams[$key])) {
                    $params[$name] = $userParams[$key];
                }
            }
        }

        return $params;
    }

    protected function extractControllerInfo(Route $route): array
    {
        $elements = explode('/', trim($route->getController(), '/'));

        Assertion::greaterOrEqualThan(count($elements), $this->segments,
            'Path has %s segments, should contains at least %s segments.');

        return $elements;
    }
}
