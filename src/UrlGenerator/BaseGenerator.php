<?php

namespace FitdevPro\FitRouter\UrlGenerator;

use FitdevPro\FitRouter\RouteCollection\IRouteCollection;

class BaseGenerator implements IUrlGenerator
{
    /** @var  IRouteCollection */
    private $routeCollection;

    /**
     * @param IRouteCollection $routeCollection
     */
    public function setRouteCollection(IRouteCollection $routeCollection)
    {
        $this->routeCollection = $routeCollection;
    }

    public function generate(string $routeController, array $params = []): string
    {
        $route = $this->routeCollection->get($routeController);

        return $route->getUrl();
    }
}
