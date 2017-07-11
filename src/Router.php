<?php

namespace FitdevPro\FitRouter;

use Assert\Assertion;
use Fig\Http\Message\RequestMethodInterface;
use FitdevPro\FitRouter\Exception\RouterException;
use FitdevPro\FitRouter\RouteFillers\IRouteFiller;
use FitdevPro\FitRouter\RouteMatcher\IRouteMatcher;
use FitdevPro\FitRouter\UrlFillers\IUrlFiller;

class Router
{
    /** @var RouteCollection */
    protected $routeCollection;
    /** @var  IRouteFiller */
    protected $routeFiller;
    /** @var  IUrlFiller */
    protected $urlFiller;
    /** @var  IRouteMatcher */
    protected $routeMatcher;

    /**
     * Router constructor.
     * @param RouteCollection $routeCollection
     * @param IRouteMatcher $routeMatcher
     * @param IRouteFiller|null $routeFiller
     * @param IUrlFiller|null $urlFiller
     */
    public function __construct(
        RouteCollection $routeCollection,
        IRouteMatcher $routeMatcher,
        IRouteFiller $routeFiller = null,
        IUrlFiller $urlFiller = null
    ) {
        $this->routeCollection = $routeCollection;
        $this->routeFiller = $routeFiller;
        $this->urlFiller = $urlFiller;
        $this->routeMatcher = $routeMatcher;
    }

    public function addRoute(Route $route)
    {
        $this->routeCollection->add($route);
    }

    public function loadRoutes(array $config)
    {
        $this->routeCollection->addMany($config);
    }

    public function matchRequest()
    {
        return $this->match($this->getRequsetUrl(), $this->getRequestMethod());
    }

    public function match($requestUrl, $requestMethod)
    {
        try {
            $route = $this->routeMatcher->match($this->routeCollection, $requestUrl, $requestMethod);
            $this->fill($route);
            return $route;
        } catch (RouterException $e) {
            return null;
        }
    }

    private function fill(Route $route)
    {
        if (!is_null($this->routeFiller)) {
            $this->routeFiller->fill($route);
        }
    }

    private function getRequestMethod()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if (isset($_POST['_method'])) {
            $_method = strtoupper($_POST['_method']);
            if (in_array($_method, array(RequestMethodInterface::METHOD_PUT, RequestMethodInterface::METHOD_DELETE),
                true)) {
                $requestMethod = $_method;
            }
        }

        return $requestMethod;
    }

    private function getRequsetUrl()
    {
        $requestUrl = $_SERVER['REQUEST_URI'];

        if (($pos = strpos($requestUrl, '?')) !== false) {
            $requestUrl = substr($requestUrl, 0, $pos);
        }

        return $requestUrl;
    }

    //-----------------------------------------------------------------------------

    public function generate($routePath, array $params = [])
    {
        $all = $this->routeCollection->getAll();

        Assertion::keyExists($all, $routePath);

        /** @var Route $route */
        $route = $all[$routePath];

        if (!is_null($this->urlFiller)) {
            $url = $this->urlFiller->fill($route, $params);
        } else {
            $url = $route->getUrl();
        }

        return $url;
    }
}
