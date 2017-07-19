<?php

namespace FitdevPro\FitRouter;

class UrlGenerator
{
    private $routeController;
    private $params;
    private $route;
    private $url = '';

    /**
     * UrlGenerator constructor.
     * @param $routeController
     * @param $params
     */
    public function __construct($routeController, $params)
    {
        $this->routeController = $routeController;
        $this->params = $params;
    }

    /**
     * @return mixed
     */
    public function getRouteController()
    {
        return $this->routeController;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param mixed $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }
}
