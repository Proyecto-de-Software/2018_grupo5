<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 07/09/18
 * Time: 00:25
 */
include_once('Matcher.php');
class MatcherRegex extends Matcher{


    function isThis($url_request) {
        $ok = preg_match($this->url_pattern, $url_request, $matches, PREG_OFFSET_CAPTURE);
        return $ok;
    }

    function getParameters($url_request) {
        // TODO: Implement getParameters() method.
    }
}