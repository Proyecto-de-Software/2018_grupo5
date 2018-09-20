<?php

abstract class Matcher {
    public $url_pattern;

    function __construct($url_pattern) {
        $this->url_pattern = '/' . $url_pattern . '/';
    }

    abstract function isThis($url_request);

    abstract function getParameters($url_request);

}