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
        # es necesario escapar el caracter / ya que es un delimitador par la regex
        $url_pattern = str_replace("/", "\/", $url_pattern);
        # se agrega los delimitadores al string, y start - end
        $this->url_pattern = "/^" . $url_pattern . "$/";
        # mas info sobre regex http://php.net/manual/es/function.preg-match.php
    }

    function isThis($url_request) {
        $ok = preg_match($this->url_pattern, $url_request, $matches, PREG_OFFSET_CAPTURE);
        return $ok;
    }

    function getParameters($url_request) {
        // TODO: Implement getParameters() method.
    }
}