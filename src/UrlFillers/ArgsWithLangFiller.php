<?php

namespace FitdevPro\FitRouter\UrlFillers;

use FitdevPro\FitRouter\Route;

class ArgsWithLangFiller extends ArgsFiller
{
    private $lang;

    /**
     * ArgsWithLangFiller constructor.
     * @param $lang
     */
    public function __construct($lang)
    {
        $this->lang = $lang;
    }


    public function fill(Route $route, array $params): string
    {
        $url = parent::fill($route, $params);
        $url = '/' . $this->lang . '/' . trim($url, '/');

        return $url;
    }
}
