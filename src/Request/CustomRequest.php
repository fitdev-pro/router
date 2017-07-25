<?php

namespace FitdevPro\FitRouter\Request;

class CustomRequest extends Request
{
    /**
     * CustomRequest constructor.
     * @param $url
     * @param $method
     */
    public function __construct(string $url, string $method)
    {
        $this->setRequsetUrl($url);
        $this->setRequestMethod($method);
    }
}
