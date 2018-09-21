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
include_once(CODE_ROOT . "/core/errors/BadControllerName.php");
include_once('MatcherRegex.php');
include_once('MatcherPath.php');
include_once('Path.php');

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
     * @throws BadControllerNameException
     */
    private function get_required_path_of_controller(){
        $regex = '/(^[A-Z]{1}.+)(Controller)/';
        $ok = preg_match($regex, $this->classAndMethod, $matches, PREG_OFFSET_CAPTURE);
        if (!$ok) {
            throw new BadControllerNameException("Controller name error : {{ " . $this->classAndMethod . " }}", "1");
        }
        # aca se quedaria en la variable unicamente con el nombre del controllador,
        # hasta antes de *Controller::*
        # ej: 'UsuariosController::AlgunMetodo',  quedaria 'UsuariosController'

        $controller = $matches[0][0];
        return  (CODE_ROOT . '/controllers/' . $controller . '.php');
    }


    /**
     * @param $url
     * @return mixed
     * @throws BadControllerNameException
     */
    function exec($url_request){
        require_once ($this->get_required_path_of_controller());
        return call_user_func($this->classAndMethod, $this->matcherInstance->getParameters($url_request));
    }

    function getUrlPattern(){
        return $this->matcherInstance->getUrl();
    }

    function isThis($url_request){
        return $this->matcherInstance->isThis($url_request);
    }
}
