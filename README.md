# FitRouter

PHP 7.0 HTTP Router Implementation.

## Installation

```
composer require fitdev-pro/router
```

## Usage

Base usage
```php
<?php
use FitdevPro\FitRouter\Router;
use FitdevPro\FitRouter\Request\HttpRequest;
use FitdevPro\FitRouter\RouteCollection\RouteCollection;
use FitdevPro\FitRouter\UrlGenerator\BaseGenerator;
use FitdevPro\FitRouter\RouteMatchers\MVCDynamicMatcher;

$routerRequest = new HttpRequest();
$routerRequest->addRequestParam('extra', 'FooBar');

$router = new Router(new RouteCollection(), new MVCDynamicMatcher(), new BaseGenerator());

$route = $router->match( $routerRequest );
```

## Contribute

Please feel free to fork and extend existing or add new plugins and send a pull request with your changes!
To establish a consistent code quality, please provide unit tests for all your changes and may adapt the documentation.

## License

The MIT License (MIT). Please see [License File](https://github.com/fitdev-pro/router/blob/master/LISENCE) for more information.
