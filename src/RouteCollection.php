<?php

namespace FitdevPro\FitRouter;

/**
 * Class RouteCollection
 * @package FitdevPro\FitRouter
 */
class RouteCollection
{
    /** @var Route[] */
    protected $routes = [];

    /**
     * @param string $resource
     * @param array $config
     */
    public function create(string $resource, array $config)
    {
        $this->add(new Route($resource, $config));
    }

    public function add(Route $route)
    {
        $this->routes[$route->getName()] = $route;
    }

    /**
     * @param array $configs
     */
    public function addMany(array $configs)
    {
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

    /**
     * @return Route[]
     */
    public function getAll()
    {
        return $this->routes;
    }
}
