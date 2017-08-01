<?php

namespace FitdevPro\FitRouter;

use FitdevPro\FitMiddleware\MiddlewareHundler;
use FitdevPro\FitMiddleware\Queue;
use FitdevPro\FitMiddleware\Resolver;
use FitdevPro\FitRouter\Exception\RouterException;
use FitdevPro\FitRouter\Middleware\IRouterMiddleware;
use FitdevPro\FitRouter\Middleware\Match\IAfterMatchMiddleware;
use FitdevPro\FitRouter\Middleware\Match\IBeforeMatchMiddleware;
use FitdevPro\FitRouter\Request\IRequest;
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

    /** @var  IRequest */
    protected $request;

    protected $midlewares = [];

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

    public function appendMiddleware(IRouterMiddleware $middleware)
    {
        $this->midlewares[] = $middleware;
    }

    public function match(IRequest $request)
    {
        try {
            $this->request = $request;
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

        return $hundler->hundle($this, $request);
    }

    protected function afterMatchHundle($route)
    {
        $hundler = $this->getMiddlewareHundler(IAfterMatchMiddleware::class);

        return $hundler->hundle($this, $route);
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

    public function getUrlGenerate(): IUrlGenerator
    {
        $this->urlGenerator->setRouteCollection($this->routeCollection);
        return $this->urlGenerator;
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
