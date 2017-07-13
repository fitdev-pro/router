<?php

namespace FitdevPro\FitRouter\UrlFillers;

use FitdevPro\FitRouter\Route;

interface IUrlFiller
{
    public function getUrl(Route $route, array $params): string;
}
