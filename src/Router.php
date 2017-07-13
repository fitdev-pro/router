<?php

namespace FitdevPro\FitRouter;

use FitdevPro\FitRouter\Exception\RouterException;
use FitdevPro\FitRouter\Request\IRequest;
use FitdevPro\FitRouter\RouteCollection\IRouteCollection;
use FitdevPro\FitRouter\RouteFillers\IRouteFiller;
use FitdevPro\FitRouter\RouteMatchers\IRouteMatcher;
use FitdevPro\FitRouter\UrlFillers\IUrlFiller;

class Router
{
    /** @var IRouteCollection */
    protected $routeCollection;
    /** @var  IRouteFiller */
    protected $routeFiller;
    /** @var  IUrlFiller */
    protected $urlFiller;
    /** @var  IRouteMatcher */
    protected $routeMatcher;

    /**
     * Router constructor.
     * @param IRouteCollection $routeCollection
     * @param IRouteMatcher $routeMatcher
     * @param IRouteFiller|null $routeFiller
     * @param IUrlFiller|null $urlFiller
     */
    public function __construct(
        IRouteCollection $routeCollection,
        IRouteMatcher $routeMatcher,
        IRouteFiller $routeFiller = null,
        IUrlFiller $urlFiller = null
    ) {
        $this->routeCollection = $routeCollection;
        $this->routeMatcher = $routeMatcher;
        $this->routeFiller = $routeFiller;
        $this->urlFiller = $urlFiller;
    }

    public function match(IRequest $request)
    {
        try {
            $route = $this->routeMatcher->match($this->routeCollection, $request);

            if (!is_null($this->routeFiller)) {
                $this->routeFiller->fill($route);
            }

        } catch (RouterException $e) {
            $route = null;
        }

        return $route;
    }

    public function generate($routePath, array $params = [])
    {
        $route = $this->routeCollection->get($routePath);

        if (!is_null($this->urlFiller)) {
            $url = $this->urlFiller->getUrl($route, $params);
        } else {
            $url = $route->getUrl();
        }

        return $url;
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
