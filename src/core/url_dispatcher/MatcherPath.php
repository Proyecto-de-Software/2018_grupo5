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

    const RE_REPLACE_INTEGER_GROUP = "(?P<%id%>^[0-9]+)";
    const re_replace_string = "(^[a-zA-Z]+)";

    const search_tag_integer = "/<(?P<id>[a-zA-Z]+):integer>/";
    const search_tag_string = "/<(?P<id>[a-zA-Z]+):(?P<type>string)>/";


    function __construct($url_lazy) {
        parent::__construct($url_lazy);
        $this->regexPattern = $this->match_and_replace_to_real_regex($url_lazy, 'integer', self::RE_REPLACE_INTEGER_GROUP);
        echo  htmlspecialchars($this->regexPattern);
    }

    private function match_and_replace_to_real_regex($url_lazy, $search_type, $replaceTo) {
        $re_search_type = str_replace("%type%",$search_type,"/<(?P<id>[a-zA-Z]+):%type%>/");
        $ok = preg_match_all(
            self::search_tag_integer,
            $this->url_pattern ,
            $matches
        );

        if ($ok) {
            foreach ($matches[1] as $key) {
                $id = $key;
                $replace = str_replace("%id%", $id, $replaceTo);
                $url_lazy = str_replace("<$id:$search_type>",$replace, $url_lazy);
            }
        }
        return $url_lazy;
    }


    function getParameters($url_request) {



    }

    function isThis($url_request) {
        return false;
    }
}