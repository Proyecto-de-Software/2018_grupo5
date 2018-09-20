<?php

class MatcherPath extends Matcher {

     const re_integer = "([0..9]+)";
     const re_string = "([a-zA-Z]+)";


    function getParameters($url_request) {
        // TODO: Implement getParameters() method.
    }

    function isThis($url_request) {
        return false;
    }
}