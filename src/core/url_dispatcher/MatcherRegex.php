<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 07/09/18
 * Time: 00:25
 */
include_once('Matcher.php');
class MatcherRegex extends Matcher{

    function __construct($url_pattern) {
        parent::__construct($url_pattern);
        $url_pattern = $this->escape_dash_characters($url_pattern);
        # se agrega los delimitadores al string, y start - end
        $this->url_pattern = "/^" . $url_pattern . "$/";
        # mas info sobre regex http://php.net/manual/es/function.preg-match.php
    }

    function isThis($url_request) {
        return preg_match($this->url_pattern, $url_request, $matches, PREG_OFFSET_CAPTURE);
    }

    function getParameters($url_request) {
        $ok = preg_match($this->url_pattern, $url_request, $matches);
        if ($ok){
            # el primer elemento no interesa, seria el path completo
            return array_slice($matches,1);
        }
        return [];
    }
}