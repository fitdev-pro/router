<?php

namespace FitdevPro\FitRouter\Middleware\Match\Before;

use FitdevPro\FitRouter\Middleware\Match\IBeforeMatchMiddleware;
use FitdevPro\FitRouter\Request\CustomRequest;
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


    public function __invoke($data, IRequest $request, callable $next)
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

        $new_request = new CustomRequest('/' . trim(join('/', $elements), '/'), $request->getRequestMethod());

        foreach( $request->getRequestParams() as $key => $value){
            $new_request->addRequestParam($key, $value);
        }

        $request = $next($data, $new_request);

        return $request;
    }
}
