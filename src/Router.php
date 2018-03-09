<?php

namespace FitdevPro\FitRouter;

use FitdevPro\FitMiddleware\MiddlewareHandler;
use FitdevPro\FitMiddleware\Queue;
use FitdevPro\FitMiddleware\Resolver;
use FitdevPro\FitRouter\Middleware\Match\IAfterMatchMiddleware;
use FitdevPro\FitRouter\Middleware\Match\IBeforeMatchMiddleware;
use FitdevPro\FitRouter\Request\IRequest as IRouterRequest;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;
use FitdevPro\FitRouter\RouteMatchers\IRouteMatcher;
use FitdevPro\FitRouter\UrlGenerator\IUrlGenerator;

class Router
{
    /** @var IRouteCollection */
    protected $routeCollection;

    /** @var  IRouteMatcher */
    protected $routeMatcher;

    /** @var IUrlGenerator */
    protected $urlGenerator;

    protected $middlewareBefore = [];
    protected $middlewareAfter = [];

    /**
     * Router constructor.
     * @param IRouteCollection $routeCollection
     * @param IRouteMatcher $routeMatcher
     * @param IUrlGenerator $urlGenerator
     */
    public function __construct(
        IRouteCollection $routeCollection,
        IRouteMatcher $routeMatcher,
        IUrlGenerator $urlGenerator
    ) {
        $this->routeCollection = $routeCollection;
        $this->routeMatcher = $routeMatcher;
        $this->urlGenerator = $urlGenerator;
    }

    public function addRoute(Route $route)
    {
        $this->routeCollection->add($route);
    }

    public function loadRoutes(array $routes)
    {
        $this->routeCollection->load($routes);
    }

    public function appendBeforeMiddleware(IBeforeMatchMiddleware $middleware)
    {
        $this->middlewareBefore[] = $middleware;
    }

    public function appendAfterMiddleware(IAfterMatchMiddleware $middleware)
    {
        $this->middlewareAfter[] = $middleware;
    }

    public function match(IRouterRequest $request)
    {
        $request = $this->beforeMatchHandle($request);
        $route = $this->routeMatcher->match($this->routeCollection, $request);
        $route = $this->afterMatchHandle($route);

        return $route;
    }

    protected function beforeMatchHandle($request)
    {
        $handler = new MiddlewareHandler(new Resolver(), new Queue());

        foreach ($this->middlewareBefore as $midleware) {
            $handler->append($midleware);
        }

        return $handler->handle($this, $request);
    }

    protected function afterMatchHandle($route)
    {
        $handler = new MiddlewareHandler(new Resolver(), new Queue());

        foreach ($this->middlewareAfter as $midleware) {
            $handler->append($midleware);
        }

        return $handler->handle($this, $route);
    }

    public function getUrlGenerate(): IUrlGenerator
    {
        $this->urlGenerator->setRouteCollection($this->routeCollection);
        return $this->urlGenerator;
    }
}
