<?php
/**
 *  Esta clase tiene que poder resolver los url path
 *      ex: /api/encuenta/<intenger:anio>/<string:persona>
 *         deberia retornar una array asociativo, con los valores matcheados.
 *          lo podemos hacer bastante siemple con substr,
 *          o usar un par de regex simples.
 *
 *  r'^api/pepe/([a-Z]+)'
 * deberia poder entender expreisiones regulares,
 * y retornarla como un array ordenado por los grupos matcheados
 *
 *
 *
 */
$MODULE_PATH = dirname(__FILE__);
include_once($MODULE_PATH . '/MatcherRegex.php');
include_once($MODULE_PATH . '/MatcherPath.php');
include_once($MODULE_PATH . '/Path.php');

class Path {

    private $classAndMethod;
    /**@var Matcher**/
    private $matcherInstance;

    function __construct($matcher, $classAndMethod) {
        $this->classAndMethod = $classAndMethod;
        $this->matcherInstance = $matcher;
    }

    /**
     * @param $regex
     * @param $function
     * @return Path
     */
    static function path_re($regex, $function) {
        $matcher = new MatcherRegex($regex);
        return new Path($matcher, $function);
    }

    /**
     * @param $path
     * @param $function
     * @return Path
     */
    static function path($path, $function) {
        $matcher = new MatcherPath($path);
        return new Path($matcher, $function);
    }

    /**
     * @return Matcher
     */
    function matcher() {
        return $this->matcherInstance;
    }

    private function import_requiered_file(){
        #TODO
        return null;
    }

    function exec($url){
        $this->import_requiered_file();
        return call_user_func($this->classAndMethod, $this->matcherInstance->getParameters($url));
    }

    function isThis($url_request){
        #TODO
    }
}
