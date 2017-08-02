<?php

namespace FitdevPro\FitRouter\Request;

interface IRequest
{
    public function getRequestMethod(): string;

    public function setRequestMethod(string $method);
    public function getRequsetUrl(): string;
    public function setRequsetUrl(string $url);

    public function getRequestParams(): array;

    public function getRequestParam(string $key, $default = null);
    public function addRequestParam(string $key, $value);
}
