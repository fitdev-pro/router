<?php

namespace FitdevPro\FitRouter\UrlFillers;

use FitdevPro\FitRouter\Route;

interface IUrlFiller
{
    public function fill(Route $route, array $params): string;
}
