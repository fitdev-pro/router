<?php

namespace FitdevPro\FitRouter\UrlGenerator;

use FitdevPro\FitMiddleware\MiddlewareHundler;
use FitdevPro\FitMiddleware\Queue;
use FitdevPro\FitMiddleware\Resolver;
use FitdevPro\FitRouter\Middleware\Generate\IAfterGenerateMiddleware;
use FitdevPro\FitRouter\Middleware\Generate\IBeforeGenerateMiddleware;
use FitdevPro\FitRouter\Middleware\IRouterMiddleware;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;

class UrlGenerator implements IUrlGenerator
{
    private $routeCollection;

    protected $midlewares = [];

    /**
     * UrlGenerator constructor.
     * @param IRouteCollection $routeCollection
     */
    public function __construct($routeCollection)
    {
        $this->routeCollection = $routeCollection;
    }

    public function appendMiddleware(IRouterMiddleware $middleware)
    {
        $this->midlewares[] = $middleware;
    }

    public function generate(string $routeController, array $params = []): string
    {
        $routeController = $this->beforeGenerateHundle($params, $routeController);

        $route = $this->routeCollection->get($routeController);

        $url = $this->afterGenerateHundle(['route' => $route, 'params' => $params], $route->getUrl());

        return $url;
    }

    protected function beforeGenerateHundle($input, $output)
    {
        $hundler = $this->getMiddlewareHundler(IBeforeGenerateMiddleware::class);

        return $hundler->hundle($input, $output);
    }

    protected function afterGenerateHundle($input, $output)
    {
        $hundler = $this->getMiddlewareHundler(IAfterGenerateMiddleware::class);

        return $hundler->hundle($input, $output);
    }

    protected function getMiddlewareHundler($type)
    {
        $hundler = new MiddlewareHundler(new Resolver(), new Queue());

        foreach ($this->midlewares as $midleware) {
            if ($midleware instanceof $type) {
                $hundler->append($midleware);
            }
        }

        return $hundler;
    }
}
