<?php

/**@example <id:integer>
 * @example <id:integer:2>   two integers
 * @example <id:integer:2,5>   min two times , max five times for integers valid = 22 , 333 ,1234 ,11111
 * @example <id:integer:1..3> integers in rage 1..3
 * @example <something:slug>
 * @example <name:string>
 * @example <name:string:>
 */

class MatcherPath extends Matcher {


    const capture_group_template = "(?P<%id%>%regex%)";

    const re_integer = "(^[0-9]+)";
    const re_string = "(^[a-zA-Z]+)";

    function getParameters($url_request) {

    }

    function isThis($url_request) {
        return false;
    }
}