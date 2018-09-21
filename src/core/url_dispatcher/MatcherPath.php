<?php

/**@example <id:integer>
 * @example <something:slug>
 * @example <name:string:>
 */

class MatcherPath extends Matcher {

    private $regexPattern;

    const RE_REPLACE_INTEGER_GROUP = "(?P<%id%>[0-9]+)";
    const RE_REPLACE_STRING_GROUP = "(?P<%id%>[a-zA-Z]+)";
    const RE_REPLACE_SLUG_GROUP = "(?P<%id%>.+)";

    const SEARCH_ID_TAGS_INTEGERS = "/<(?P<id>[a-zA-Z]+):integer>/";
    const SEARCH_ID_TAGS_STRING = "/<(?P<id>[a-zA-Z]+):string>/";
    const SEARCH_ID_TAGS_SLUG = "/<(?P<id>[a-zA-Z]+):slug>/";


    function __construct($url_lazy) {
        parent::__construct($url_lazy);
        $this->regexPattern = '/^' . $this->escape_dash_characters($url_lazy) . '$/';
        $this->generate_integers_re();
        $this->generate_strings_re();
        $this->generate_slug_re();
    }

    private function generate_strings_re() {
        $this->regexPattern = $this->match_and_replace_to_real_regex(
            $this->regexPattern,
            self::SEARCH_ID_TAGS_STRING,
            'string',
            self::RE_REPLACE_STRING_GROUP
        );
    }

    private function generate_slug_re() {
        $this->regexPattern = $this->match_and_replace_to_real_regex(
            $this->regexPattern,
            self::SEARCH_ID_TAGS_SLUG,
            'slug',
            self::RE_REPLACE_SLUG_GROUP
        );
    }


    private function generate_integers_re() {
        $this->regexPattern = $this->match_and_replace_to_real_regex(
            $this->regexPattern,
            self::SEARCH_ID_TAGS_INTEGERS,
            'integer',
            self::RE_REPLACE_INTEGER_GROUP
        );

    }

    private function match_and_replace_to_real_regex($url_lazy, $seatch_id_tag_type ,$search_type, $replaceTo) {
        $re_search_type = str_replace("%type%",$search_type,"/<(?P<id>[a-zA-Z]+):%type%>/");
        $ok = preg_match_all(
            $seatch_id_tag_type,
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
        $params = [];
        $ok = preg_match_all(
            $this->regexPattern,
            $url_request ,
            $matches,
            PREG_PATTERN_ORDER
        );
        /**@todo  find a best way to do this*/
        foreach ($matches as $key=>$value) {
            foreach ($value as $key2=>$value2) {
                $params[$key] = $value2;
            }
        }
        return $params;
    }

    function isThis($url_request) {
        $ok = preg_match_all($this->regexPattern, $url_request, $matches);
        return $ok;
    }
}