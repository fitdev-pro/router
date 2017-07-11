<?php

namespace FitdevPro\FitRouter;

use Assert\Assertion;
use Fig\Http\Message\RequestMethodInterface;

class Route
{
    protected $pathParser;

    /**
     * Accepted HTTP methods for this route.
     * @var string[]
     */
    private $methods = array(
        RequestMethodInterface::METHOD_GET,
        RequestMethodInterface::METHOD_POST,
        RequestMethodInterface::METHOD_PUT,
        RequestMethodInterface::METHOD_DELETE,
    );

    private $url;

    private $controller;

    private $config;

    private $parameters = array();

    private $validation = array();

    /**
     * @param       $resource
     * @param array $config
     */
    public function __construct(string $resource, array $config)
    {
        $this->url = '/' . trim($resource, '/');
        $this->config  = $config;

        Assertion::keyExists($config, 'controller');

        $this->controller = $config['controller'];

        if (isset($config['methods'])) {
            $this->methods = (array)$config['methods'];
        }

        if (isset($config['parameters'])) {
            $this->parameters = $config['parameters'];
        }

        if (isset($config['validation'])) {
            $this->validation = $config['validation'];
        }
    }

    public function getUrl() : string
    {
        return $this->url;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getName()
    {
        return $this->getController();
    }

    public function getValidation(): array
    {
        return $this->validation;
    }

    public function getParameters() : array
    {
        return $this->parameters;
    }

    public function addParameters(array $parameters)
    {
        $this->parameters = array_merge($this->parameters, $parameters);
    }
}
