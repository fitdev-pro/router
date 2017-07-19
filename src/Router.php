<?php

namespace FitdevPro\FitRouter;

use FitdevPro\FitMiddleware\MiddlewareHundler;
use FitdevPro\FitMiddleware\Queue;
use FitdevPro\FitMiddleware\Resolver;
use FitdevPro\FitRouter\Exception\RouterException;
use FitdevPro\FitRouter\Middleware\Generate\IAfterGenerateMiddleware;
use FitdevPro\FitRouter\Middleware\Generate\IBeforeGenerateMiddleware;
use FitdevPro\FitRouter\Middleware\IRouterMiddleware;
use FitdevPro\FitRouter\Middleware\Match\IAfterMatchMiddleware;
use FitdevPro\FitRouter\Middleware\Match\IBeforeMatchMiddleware;
use FitdevPro\FitRouter\Request\IRequest;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;
use FitdevPro\FitRouter\RouteMatchers\IRouteMatcher;

class Router
{
    /** @var IRouteCollection */
    protected $routeCollection;

    /** @var  IRouteMatcher */
    protected $routeMatcher;

    protected $midlewares = [];

    /**
     * Router constructor.
     * @param IRouteCollection $routeCollection
     * @param IRouteMatcher $routeMatcher
     */
    public function __construct(
        IRouteCollection $routeCollection,
        IRouteMatcher $routeMatcher
    ) {
        $this->routeCollection = $routeCollection;
        $this->routeMatcher = $routeMatcher;
    }

    public function appendMiddleware(IRouterMiddleware $middleware)
    {
        $this->midlewares[] = $middleware;
    }

    public function match(IRequest $request)
    {
        try {
            $request = $this->beforeMatchHundle($request);
            $route = $this->routeMatcher->match($this->routeCollection, $request);
            $route = $this->afterMatchHundle($route);
        } catch (RouterException $e) {
            $route = null;
        }

        return $route;
    }

    protected function beforeMatchHundle($request)
    {
        $hundler = $this->getMiddlewareHundler(IBeforeMatchMiddleware::class);

        return $hundler->hundle($request);
    }

    protected function afterMatchHundle($route)
    {
        $hundler = $this->getMiddlewareHundler(IAfterMatchMiddleware::class);

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

    public function generate($routeController, array $params = [])
    {
        /** @var UrlGenerator $url */
        $url = new UrlGenerator($routeController, $params);

        $url = $this->beforeGenerateHundle($url);

        $url->setRoute($this->routeCollection->get($url->getRouteController()));

        $url = $this->afterGenerateHundle($url);

        return $url->getUrl();
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

    public function addRoute(Route $route)
    {
        $this->routeCollection->add($route);
    }

    public function loadRoutes(array $routes)
    {
        $this->routeCollection->load($routes);
    }
}
