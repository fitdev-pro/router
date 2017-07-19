<?php

namespace FitdevPro\FitRouter\UrlFillers;

use FitdevPro\FitRouter\Exception\UrlFillerException;
use FitdevPro\FitRouter\Middleware\Generate\IAfterGenerateMiddleware;
use FitdevPro\FitRouter\UrlGenerator;

class AddArgs implements IAfterGenerateMiddleware
{
    const
        TOO_FEW_PARAMS = '1815010601',
        NO_USER_PARAMS = '1815010602';

    public function __invoke(UrlGenerator $generator, callable $next)
    {
        $url = $generator->getUrl();

        $params = $generator->getParams();

        // replace route url with given parameters
        if ($params && preg_match_all('/:(\w+)/', $url, $param_keys)) {
            // grab array with matches
            $param_keys = $param_keys[1];

            if (count($param_keys) > count($params)) {
                throw new UrlFillerException('The number of parameters does not match number of values for procedure.',
                    static::TOO_FEW_PARAMS);
            }

            // loop trough parameter names, store matching value in $params array
            foreach ($param_keys as $key) {
                if (isset($params[$key])) {
                    $url = preg_replace('/:(\w+)/', $params[$key], $url, 1);
                } else {
                    throw new UrlFillerException("Parameter '$key' does not exist in sended parameters.",
                        static::NO_USER_PARAMS);
                }
            }
        }

        $generator = $next($generator);

        return $generator;
    }
}
