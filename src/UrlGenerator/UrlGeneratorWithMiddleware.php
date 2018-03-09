<?php

namespace FitdevPro\FitRouter\UrlGenerator;

use FitdevPro\FitMiddleware\MiddlewareHundler;
use FitdevPro\FitMiddleware\Queue;
use FitdevPro\FitMiddleware\Resolver;
use FitdevPro\FitRouter\Middleware\Generate\IAfterGenerateMiddleware;
use FitdevPro\FitRouter\Middleware\Generate\IBeforeGenerateMiddleware;
use FitdevPro\FitRouter\Middleware\IRouterMiddleware;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;

class UrlGeneratorWithMiddleware implements IUrlGenerator
{
    /** @var  IRouteCollection */
    private $routeCollection;
    private $midlewaresBefore = [];
    private $midlewaresAfter = [];

    public function appendBeforeMiddleware(IBeforeGenerateMiddleware $middleware)
    {
        $this->midlewaresBefore[] = $middleware;
    }

    public function appendAfterMiddleware(IAfterGenerateMiddleware $middleware)
    {
        $this->midlewaresAfter[] = $middleware;
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
        $routeController = $this->beforeGenerateHundle($params, $routeController);

        $route = $this->routeCollection->get($routeController);

        $url = $this->afterGenerateHundle(['route' => $route, 'params' => $params], $route->getUrl());

        return $url;
    }

    private function beforeGenerateHundle($input, $output)
    {
        $hundler = new MiddlewareHundler(new Resolver(), new Queue());

        foreach ($this->midlewaresBefore as $midleware) {
            $hundler->append($midleware);
        }

        return $hundler->hundle($input, $output);
    }

    private function afterGenerateHundle($input, $output)
    {
        $hundler = new MiddlewareHundler(new Resolver(), new Queue());

        foreach ($this->midlewaresAfter as $midleware) {
            $hundler->append($midleware);
        }

        return $hundler->hundle($input, $output);
    }
}
