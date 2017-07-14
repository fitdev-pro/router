<?php

namespace FitdevPro\FitRouter\UrlFillers;

use FitdevPro\FitRouter\Route;

class ArgsWithLangFiller extends ArgsFiller
{
    const
        TOO_FEW_PARAMS = '1815120601',
        NO_USER_PARAMS = '1815120602';

    private $lang;

    /**
     * ArgsWithLangFiller constructor.
     * @param $lang
     */
    public function __construct($lang)
    {
        $this->lang = $lang;
    }


    public function getUrl(Route $route, array $params): string
    {
        $url = parent::getUrl($route, $params);
        $url = '/' . $this->lang . '/' . trim($url, '/');

        return $url;
    }
}
