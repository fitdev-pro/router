<?php

namespace FitdevPro\FitRouter\Request;

interface IRequest
{
    public function getRequestMethod(): string;
    public function getRequsetUrl(): string;

    public function setRequsetUrl(string $url);

    public function setRequestMethod(string $method);
}
