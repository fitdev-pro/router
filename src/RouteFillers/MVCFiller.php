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

        $out['attr'] = $this->extractParamsValues($route);

        $route->addParameters($out);
    }

    protected function extractParamsValues(Route $route)
    {
        $params = array();
        $matches = $route->getParameters()['params'];

        array_shift($matches);

        if (preg_match_all('/:([\w-]+)/', $route->getUrl(), $argument_keys)) {
            // grab array with matches
            $argument_keys = $argument_keys[1];

            if (count($argument_keys) !== count($matches)) {
                throw new RouteFillerException('To many args.');
            }

            foreach ($argument_keys as $key => $name) {
                if (isset($matches[$key])) {
                    $params[$name] = $matches[$key];
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
