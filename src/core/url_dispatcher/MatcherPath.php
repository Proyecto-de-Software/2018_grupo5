<?php

/**@example <id:integer>
 * @example <id:integer:2>   two integers #not implemented
 * @example <id:integer:2,5>   min two times , max five times for integers valid = 22 , 333 ,1234 ,11111 #not implemented
 * @example <id:integer:1..3> integers in rage 1..3 #not implemented
 * @example <something:slug> #not implemented
 * @example <name:string>
 * @example <name:string:> #not implemented
 */

class MatcherPath extends Matcher {

    private $regexPattern;
    const capture_group_template = "(?P<%id%>%regex%)";

    const re_replace_integer = "(^[0-9]+)";
    const re_replace_string = "(^[a-zA-Z]+)";

    const search_tag_integer = "/<(?P<id>[a-zA-Z]+):(?P<type>integer)>/g";
    const search_tag_string = "/<(?P<id>[a-zA-Z]+):(?P<type>string)>/g";


    function __construct($url_pattern) {
        parent::__construct($url_pattern);
        $this->regexPattern = $url_pattern;
    }



    function getParameters($url_request) {



    }

    function isThis($url_request) {
        return false;
    }
}