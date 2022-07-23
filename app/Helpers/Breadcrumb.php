<?php

namespace App\Helpers;


class Breadcrumb
{
    function __construct(
        public $title,
        public $url
    ) {}
}