<?php

namespace FitdevPro\FitRouter\UrlGenerator;

use FitdevPro\FitMiddleware\MiddlewareHandler;
use FitdevPro\FitMiddleware\Queue;
use FitdevPro\FitMiddleware\Resolver;
use FitdevPro\FitRouter\Middleware\Generate\IAfterGenerateMiddleware;
use FitdevPro\FitRouter\Middleware\Generate\IBeforeGenerateMiddleware;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;

class UrlGeneratorWithMiddleware implements IUrlGenerator
{
    /** @var  IRouteCollection */
    private $routeCollection;
    private $middlewareBefore = [];
    private $middlewareAfter = [];

    public function appendBeforeMiddleware(IBeforeGenerateMiddleware $middleware)
    {
        $this->middlewareBefore[] = $middleware;
    }

    public function appendAfterMiddleware(IAfterGenerateMiddleware $middleware)
    {
        $this->middlewareAfter[] = $middleware;
    }

    /**
     * @param IRouteCollection $routeCollection
     */
    public function setRouteCollection(IRouteCollection $routeCollection)
    {
        $this->routeCollection = $routeCollection;
    }

    public function generate(string $routeController, array $params = []): string
    {
        $routeController = $this->beforeGenerateHandle($params, $routeController);

        $route = $this->routeCollection->get($routeController);

        $url = $this->afterGenerateHandle(['route' => $route, 'params' => $params], $route->getUrl());

        return $url;
    }

    private function beforeGenerateHandle($input, $output)
    {
        $handler = new MiddlewareHandler(new Resolver(), new Queue());

        foreach ($this->middlewareBefore as $middleware) {
            $handler->append($middleware);
        }

        return $handler->handle($input, $output);
    }

    private function afterGenerateHandle($input, $output)
    {
        $handler = new MiddlewareHandler(new Resolver(), new Queue());

        foreach ($this->middlewareAfter as $middleware) {
            $handler->append($middleware);
        }

        return $handler->handle($input, $output);
    }
}
