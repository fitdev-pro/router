<?php

namespace FitdevPro\FitRouter\Exception;


class NotFoundException extends MatcherException
{
    protected $message = 'Rout not found.';
}
