<?php

namespace FitdevPro\FitRouter\Request;

interface IRequest
{
    public function getRequestMethod(): string;
    public function getRequsetUrl(): string;
    public function getRequestParams(): array;
    public function getRequestParam(string $key, $default = null);
    public function addRequestParam(string $key, $value);

    public function setRequestUrl(string $url);

    public function setRequestMethod(string $method);
}
