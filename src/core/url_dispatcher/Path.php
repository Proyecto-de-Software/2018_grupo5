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
include_once('core/url_dispatcher/MatcherRegex.php');
include_once('core/url_dispatcher/MatcherPath.php');
include_once('core/url_dispatcher/Path.php');
class Path {

    private $function;
    private $matcherInstance;

    function __construct($matcher, $function) {
        $this->function = $function;
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
        #FIXME: hay que importar los requiered_oonce para las vistas !!!!
        return call_user_func($this->function, $this->matcherInstance->getParameters($url));
    }

    function isThis($url_request){
        #TODO
    }
}
