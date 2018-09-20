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

    /**@return Matcher */
    function matcher() {
        return $this->matcherInstance;
    }


    /**
     * @throws BadControllerNameException
     */
    private function import_required_controller(){
        $regex = '/(^[A-Z]{1}.+)(Controller)/';
        $ok = preg_match($regex, $this->classAndMethod, $matches, PREG_OFFSET_CAPTURE);
        if (!$ok) {
            throw new BadControllerNameException("Controller name error","1");
        }
        # aca se quedaria en la variable unicamente con el nombre del controllador,
        # hasta antes de *Controller::*
        # ej: 'UsuariosController::AlgunMetodo',  quedaria 'Usuarios'

        /**@todo TEST THIS PLEASE*/

        $controller = $matches[0][0];
        include_once (CODE_ROOT . '/controllers/' . $controller . '.php');
    }


    /**
     * @param $url
     * @return mixed
     * @throws BadControllerNameException
     */
    function exec($url){
        $this->import_required_controller();
        return call_user_func($this->classAndMethod, $this->matcherInstance->getParameters($url));
    }

    function isThis($url_request){
        #TODO
    }
}
