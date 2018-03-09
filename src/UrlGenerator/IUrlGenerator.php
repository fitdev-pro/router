<?php

namespace FitdevPro\FitRouter\UrlGenerator;

use FitdevPro\FitRouter\RouteCollection\IRouteCollection;

interface IUrlGenerator
{
    public function setRouteCollection(IRouteCollection $routeCollection);

    public function generate(string $routeController, array $params = []): string;
}
