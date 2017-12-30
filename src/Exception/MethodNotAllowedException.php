<?php

namespace FitdevPro\FitRouter\Exception;


class MethodNotAllowedException extends MatcherException
{
    protected $message = 'Method not allowed.';
}
