<?php

namespace FitdevPro\FitRouter\Middleware\Match\Before;

use FitdevPro\FitRouter\Middleware\Match\IBeforeMatchMiddleware;
use FitdevPro\FitRouter\Request\CustomRequest;
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

    public function __invoke($data, IRequest $request, callable $next)
    {
        $elements = explode('/', trim($request->getRequsetUrl(), '/'));

        if (!isset($elements[0]) || $elements[0] == '') {
            $elements[0] = $this->controller;
        }

        if (!isset($elements[1]) || $elements[1] == '') {
            $elements[1] = $this->action;
        }

        $new_request = new CustomRequest('/' . trim(join('/', $elements), '/'), $request->getRequestMethod());

        foreach( $request->getRequestParams() as $key => $value){
            $new_request->addRequestParam($key, $value);
        }

        $request = $next($data, $new_request);

        return $request;
    }
}
