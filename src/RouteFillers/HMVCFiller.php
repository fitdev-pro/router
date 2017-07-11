<?php

namespace FitdevPro\FitRouter\RouteFillers;

use FitdevPro\FitRouter\Route;

class HMVCFiller extends MVCFiller
{
    protected $segments = 3;

    public function fill(Route $route)
    {
        $out = [];

        $path = $this->extractControllerInfo($route);

        $out['module'] = array_shift($path);
        $out['controller'] = array_shift($path);
        $out['action'] = array_shift($path);

        $out['attr'] = $this->extractParamsValues($route);

        $route->addParameters($out);
    }
}
