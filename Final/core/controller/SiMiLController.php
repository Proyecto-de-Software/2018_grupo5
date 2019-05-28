<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 27/05/19
 * Time: 20:33
 */
require_once(FW_CODE_ROOT . "/vendor/twig/lib/Twig/Autoloader.php");

abstract class SiMiLController {
    public $twig;

    function __construct() {
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem(CODE_ROOT . "/app/templates");
        $this->twig = new Twig_Environment($loader);

    }

    protected function include_defaults_twig_params(&$parameters) {
        $parameters['DEBUG'] = DEBUG;
        $parameters['CSRF_TOKEN'] = CSRF_TOKEN;
        $parameters['REQUEST_PROCESS_TIME'] = number_format((float)(microtime_float() - START_REQUEST_MICROTIME), 2);
        $parameters['PAGE_RENDER_START_TIME'] = time();
        $parameters['SETTINGS'] = SETTINGS;
        $parameters['SIMIL_CONTROLLER'] = $this;
    }

    /**
     * Overwrite this method in subclasses
     * @param $parameters
     */
    protected function add_defaults_twig_params(&$parameters){
        return;
    }


    public function twig_render($path, $parameters) {
        # es metedo intenta cubrir el render de twig, para
        # logear los errore si es necesaario, en algun momento
        $this->include_defaults_twig_params($parameters);
        $this->add_defaults_twig_params($parameters);
        try {
            return $this->twig->render($path, $parameters);
        } catch (Twig_Error_Loader $e) {
            return "#Twig_Error_Loader:  " . $e;
        } catch (Twig_Error_Runtime $e) {
            return "#Twig_Error_Runtime:  " . $e;
        } catch (Twig_Error_Syntax $e) {
            return "#Twig_Error_Syntax:  " . $e;
        }
    }


    public function jsonResponse($data) {
        header("HTTP/1.1 200 OK");
        header('Content-Type: application/json');
        return json_encode($data);
    }

    public function redirect($url) {
        header("Location: " . $url);
        die();
    }

    public function camelCaseToSnake($string) {
        return strtolower(preg_replace("/(?<=\w)(?=[A-Z])/", "_$1", lcfirst($string)));
    }

}