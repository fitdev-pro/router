<?php

namespace FitdevPro\FitRouter\Request;

interface IRequest
{
    public function getRequestMethod(): string;
    public function getRequsetUrl(): string;
    public function getRequestParams(): array;
    public function getRequestParam(string $key, $default = null);
}
