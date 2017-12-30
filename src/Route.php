<?php

namespace FitdevPro\FitRouter;

use Assert\Assertion;
use Fig\Http\Message\RequestMethodInterface;

class Route
{
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

    private $alias;

    private $parameters = array();

    private $validation = array();

    /**
     * Route constructor.
     * @param string $url
     * @param array $config
     */
    public function __construct(string $url, string $controller, array $config = [])
    {
        $this->setUrl($url);
        $this->setController($controller);
        $this->setAlias($config);
        $this->setMethods($config);
        $this->setParams($config);
        $this->setParamValidation($config);
    }

    private function setUrl(string $url)
    {
        $this->url = '/' . trim($url, '/');
    }

    private function setController($controller)
    {
        $this->controller = $controller;
    }

    private function setAlias(array $config)
    {
        if (isset($config['alias'])) {
            Assertion::string($config['alias'], 'Route config value "alias" expected to be string, type %s given.');

            $this->alias = $config['alias'];
        }
    }

    private function setMethods(array $config)
    {
        if (isset($config['methods'])) {
            if (is_string($config['methods'])) {
                $config['methods'] = [$config['methods']];
            }

            Assertion::isArray($config['methods'], 'Route config value "methods" expected to be array.');

            $this->methods = $config['methods'];
        }
    }

    private function setParams(array $config)
    {
        if (isset($config['parameters'])) {
            Assertion::isArray($config['parameters'], 'Route config value "parameters" expected to be array.');

            $this->parameters = $config['parameters'];
        }
    }

    private function setParamValidation(array $config)
    {
        if (isset($config['param-validation'])) {
            Assertion::isArray($config['param-validation'],
                'Route config value "param-validation" expected to be array.');

            $this->validation = $config['param-validation'];
        }
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getAlias(): string
    {
        if (is_null($this->alias)) {
            return $this->getController();
        }

        return $this->alias;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getParameter(string $key, $default = null)
    {
        if (isset($this->parameters[$key])) {
            return $this->parameters[$key];
        }
        return $default;
    }

    public function addParameters(array $parameters)
    {
        $this->parameters = array_merge($this->parameters, $parameters);
    }

    public function getParamValidation(): array
    {
        return $this->validation;
    }

}
