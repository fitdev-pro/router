<?php

namespace FitdevPro\FitRouter\Request;

abstract class Request implements IRequest
{
    protected $url;
    protected $method;
    protected $param = [];


    public function getRequestMethod(): string
    {
        return $this->method;
    }

    public function getRequsetUrl(): string
    {
        return $this->url;
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
