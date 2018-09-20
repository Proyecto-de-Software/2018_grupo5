<?php

abstract class Matcher {
    public $url_pattern;

    function __construct($url_pattern) {
        $this->url_pattern = $url_pattern;
    }

    function escape_dash_characters($string){
        return str_replace("/", "\/", $string);
    }

    abstract function isThis($url_request);

    abstract function getParameters($url_request);

}