<?php

namespace FitdevPro\FitRouter\UrlFillers;

use FitdevPro\FitRouter\Exception\UrlFillerException;
use FitdevPro\FitRouter\Route;

class ArgsFiller implements IUrlFiller
{

    public function fill(Route $route, array $params): string
    {
        $url = $route->getUrl();

        // replace route url with given parameters
        if ($params && preg_match_all('/:(\w+)/', $url, $param_keys)) {
            // grab array with matches
            $param_keys = $param_keys[1];

            if (count($param_keys) > count($params)) {
                throw new UrlFillerException('The number of parameters does not match number of values for procedure.');
            }

            // loop trough parameter names, store matching value in $params array
            foreach ($param_keys as $key) {
                if (isset($params[$key])) {
                    $url = preg_replace('/:(\w+)/', $params[$key], $url, 1);
                } else {
                    throw new UrlFillerException("Parameter '$key' does not exist in sended parameters.");
                }
            }
        }

        return $url;
    }
}
