<?php

namespace FitdevPro\FitRouter\RouteCollection;

use FitdevPro\FitRouter\Route;

/**
 * Interface IRouteCollection
 * @package FitdevPro\FitRouter\RouteCollection
 */
interface IRouteCollection
{
    public function add(Route $route);

    public function load(array $configs);

    public function get(string $name): Route;

    public function getAll();
}
