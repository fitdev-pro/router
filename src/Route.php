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

    private $name;

    private $config;

    private $parameters = array();

    private $validation = array();

    /**
     * Route constructor.
     * @param string $url
     * @param array $config
     */
    public function __construct(string $url, array $config)
    {
        $this->url = '/' . trim($url, '/');
        $this->config  = $config;

        Assertion::keyExists($config, 'controller');
        Assertion::string($config['controller']);

        $this->controller = $config['controller'];

        if (isset($config['name'])) {
            Assertion::string($config['name']);

            $this->name = $config['name'];
        }

        if (isset($config['methods'])) {
            if (is_string($config['methods'])) {
                $config['methods'] = [$config['methods']];
            }

            Assertion::isArray($config['methods']);

            $this->methods = $config['methods'];
        }

        if (isset($config['parameters'])) {
            Assertion::isArray($config['parameters']);

            $this->parameters = $config['parameters'];
        }

        if (isset($config['validation'])) {
            Assertion::isArray($config['validation']);

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
        if (is_null($this->name)) {
            return $this->getController();
        }

        return $this->name;
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
