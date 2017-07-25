<?php

namespace FitdevPro\FitRouter\Request;

abstract class Request implements IRequest
{
    private $url;
    private $method;
    private $attr = [];


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
        return $this->attr;
    }

    public function addRequestParam(string $key, $value)
    {
        $this->attr[$key] = $value;
    }
}
