<?php

namespace FitdevPro\FitRouter\UrlGenerator;

interface IUrlGenerator
{
    public function generate(string $routeController, array $params = []): string;
}
