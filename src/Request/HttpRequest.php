<?php

namespace FitdevPro\FitRouter\Request;

use Fig\Http\Message\RequestMethodInterface;

class HttpRequest implements IRequest
{
    public function getRequestMethod(): string
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if (isset($_POST['_method'])) {
            $_method = strtoupper($_POST['_method']);
            if (in_array(
                $_method,
                array(RequestMethodInterface::METHOD_PUT, RequestMethodInterface::METHOD_DELETE),
                true)
            ) {
                $requestMethod = $_method;
            }
        }

        return $requestMethod;
    }

    public function getRequsetUrl(): string
    {
        $requestUrl = $_SERVER['REQUEST_URI'];

        if (($pos = strpos($requestUrl, '?')) !== false) {
            $requestUrl = substr($requestUrl, 0, $pos);
        }

        return $requestUrl;
    }

}
