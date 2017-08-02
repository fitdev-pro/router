<?php

namespace FitdevPro\FitRouter\Request;

abstract class Request implements IRequest
{
    private $url;
    private $method;
    private $param = [];


    public function getRequestMethod(): string
    {
        return $this->method;
    }

    public function getRequsetUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setRequsetUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * @param string $method
     */
    public function setRequestMethod(string $method)
    {
        $this->method = $method;
    }

    public function getRequestParams(): array
    {
        return $this->param;
    }

    public function getRequestParam(string $key, $default = null)
    {
        if (isset($this->param[$key])) {
            return $this->param[$key];
        }
        return $default;
    }

    public function addRequestParam(string $key, $value)
    {
        $this->param[$key] = $value;
    }
}
