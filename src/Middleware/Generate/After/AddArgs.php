<?php

namespace FitdevPro\FitRouter\Middleware\Generate\After;

use FitdevPro\FitRouter\Exception\MiddlewareException;
use FitdevPro\FitRouter\Middleware\Generate\IAfterGenerateMiddleware;

class AddArgs implements IAfterGenerateMiddleware
{
    const
        TOO_FEW_PARAMS = '1815010601',
        NO_USER_PARAMS = '1815010602';

    public function __invoke(array $data, callable $next)
    {
        $url = $data['url'];
        $params = $data['params'];

        // replace route url with given parameters
        if ($params && preg_match_all('/:(\w+)/', $url, $param_keys)) {
            // grab array with matches
            $param_keys = $param_keys[1];

            if (count($param_keys) > count($params)) {
                throw new MiddlewareException('The number of parameters does not match number of values for procedure.',
                    static::TOO_FEW_PARAMS);
            }

            // loop trough parameter names, store matching value in $params array
            foreach ($param_keys as $key) {
                if (isset($params[$key])) {
                    $url = preg_replace('/:(\w+)/', $params[$key], $url, 1);
                } else {
                    throw new MiddlewareException("Parameter '$key' does not exist in sended parameters.",
                        static::NO_USER_PARAMS);
                }
            }

            $data['url'] = $url;
        }


        $data = $next($data);

        return $data;
    }
}
