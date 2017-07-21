<?php

namespace FitdevPro\FitRouter\UrlGenerator;

use FitdevPro\FitMiddleware\MiddlewareHundler;
use FitdevPro\FitMiddleware\Queue;
use FitdevPro\FitMiddleware\Resolver;
use FitdevPro\FitRouter\Exception\MiddlewareException;
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
        $data = ['routeController' => $routeController, 'params' => $params];

        $data = $this->beforeGenerateHundle($data);

        if (!isset($data['routeController'])) {
            throw new MiddlewareException('routeController is not defined in output data from before generate middleware.');
        }

        $route = $this->routeCollection->get($data['routeController']);
        $data['route'] = $route;
        $data['url'] = $route->getUrl();

        $data = $this->afterGenerateHundle($data);

        if (!isset($data['url'])) {
            throw new MiddlewareException('url is not defined in output data from after generate middleware.');
        }

        return $data['url'];
    }

    protected function beforeGenerateHundle($request)
    {
        $hundler = $this->getMiddlewareHundler(IBeforeGenerateMiddleware::class);

        return $hundler->hundle($request);
    }

    protected function afterGenerateHundle($route)
    {
        $hundler = $this->getMiddlewareHundler(IAfterGenerateMiddleware::class);

        return $hundler->hundle($route);
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
