<?php

namespace FitdevPro\FitRouter\Request;

class CustomRequest implements IRequest
{
    private $url;
    private $method;

    /**
     * CustomRequest constructor.
     * @param $url
     * @param $method
     */
    public function __construct(string $url, string $method)
    {
        $this->url = $url;
        $this->method = $method;
    }


    public function getRequestMethod(): string
    {
        return $this->method;
    }

    public function getRequsetUrl(): string
    {
        return $this->url;
    }
}
