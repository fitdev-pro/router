<?php

namespace FitdevPro\FitRouter\RouteCollection;

use Assert\Assertion;
use FitdevPro\FitRouter\Route;

/**
 * Class RouteCollection
 * @package FitdevPro\FitRouter
 */
class RouteCollection implements IRouteCollection
{
    /** @var Route[] */
    protected $routes = [];

    public function add(Route $route)
    {
        $this->routes[$route->getName()] = $route;
    }

    /**
     * @param string $resource
     * @param array $config
     */
    public function create(string $resource, array $config)
    {
        $this->add(new Route($resource, $config));
    }

    /**
     * @param array $configs
     */
    public function load(array $configs)
    {
        Assertion::keyExists($configs, 'routeCollection');

        foreach ($configs['routeCollection'] as $url => $config) {
            if (isset($config['group'])) {
                $this->addRouteGroup($url, $config);
            } else {
                $this->create($url, $config);
            }
        }
    }

    private function addRouteGroup($url, $config)
    {
        $pathPrefix = '';
        if (isset($config['controller'])) {
            $pathPrefix = $config['controller'];
        }
        foreach ($config['group'] as $namePart => $configPart) {
            $namePart = $url . $namePart;

            if (isset($configPart['controller'])) {
                $configPart['controller'] = $pathPrefix . $configPart['controller'];
            } else {
                $configPart['controller'] = $pathPrefix;
            }

            if (isset($configPart['group'])) {
                $this->addRouteGroup($namePart, $configPart);
            } else {
                $this->create($namePart, $configPart);
            }
        }
    }

    //-----------------------------------------------------------------------------

    /**
     * @param string $name
     * @return Route
     */
    public function get(string $name): Route
    {
        Assertion::keyExists($this->routes, $name);

        return $this->routes[$name];
    }

    /**
     * @return Route[]
     */
    public function getAll()
    {
        return $this->routes;
    }
}
