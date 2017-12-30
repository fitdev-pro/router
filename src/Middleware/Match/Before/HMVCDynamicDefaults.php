<?php

namespace FitdevPro\FitRouter\Middleware\Match\Before;

use FitdevPro\FitRouter\Middleware\Match\IBeforeMatchMiddleware;
use FitdevPro\FitRouter\Request\IRequest;

class HMVCDynamicDefaults implements IBeforeMatchMiddleware
{
    private $module;
    private $controller;
    private $action;

    /**
     * HMVCDynamicDefaults constructor.
     * @param $module
     */
    public function __construct(string $module, string $confroller, string $action)
    {
        $this->module = $module;
        $this->controller = $confroller;
        $this->action = $action;
    }


    public function __invoke($router, IRequest $request, callable $next)
    {
        $elements = explode('/', trim($request->getRequsetUrl(), '/'));

        if (!isset($elements[0]) || $elements[0] == '') {
            $elements[0] = $this->module;
        }

        if (!isset($elements[1]) || $elements[1] == '') {
            $elements[1] = $this->controller;
        }

        if (!isset($elements[2]) || $elements[2] == '') {
            $elements[2] = $this->action;
        }

        $request->setRequestUrl('/' . trim(join('/', $elements), '/'));

        $request = $next($router, $request);

        return $request;
    }
}
