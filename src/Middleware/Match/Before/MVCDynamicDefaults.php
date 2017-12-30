<?php

namespace FitdevPro\FitRouter\Middleware\Match\Before;

use FitdevPro\FitRouter\Middleware\Match\IBeforeMatchMiddleware;
use FitdevPro\FitRouter\Request\IRequest;

class MVCDynamicDefaults implements IBeforeMatchMiddleware
{
    private $controller;
    private $action;

    /**
     * MVCDynamicDefaults constructor.
     * @param $controller
     * @param $action
     */
    public function __construct(string $controller, string $action)
    {
        $this->controller = $controller;
        $this->action = $action;
    }

    public function __invoke($router, IRequest $request, callable $next)
    {
        $elements = explode('/', trim($request->getRequsetUrl(), '/'));

        if (!isset($elements[0]) || $elements[0] == '') {
            $elements[0] = $this->controller;
        }

        if (!isset($elements[1]) || $elements[1] == '') {
            $elements[1] = $this->action;
        }

        $request->setRequestUrl('/' . trim(join('/', $elements), '/'));

        $request = $next($router, $request);

        return $request;
    }
}
