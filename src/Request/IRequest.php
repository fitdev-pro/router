<?php

namespace FitdevPro\FitRouter\Request;

interface IRequest
{
    public function getRequestMethod(): string;

    public function getRequestParams(): array;
    public function getRequsetUrl(): string;
    public function setRequsetUrl(string $url);
    public function setRequestMethod(string $method);

    public function addRequestParam(string $key, $value);
}
