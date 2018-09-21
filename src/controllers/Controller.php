<?php
namespace controllers;

require_once(__DIR__ . "/../../vendor/twig/lib/Twig/Autoloader.php");

use Twig_Autoloader;
use Twig_Environment;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;
use Twig_Loader_Filesystem;



abstract class Controller {
    public $twig;

    public function __construct() {
        //Usando Twig, envio de parametros a archivo html dentro de folder "templates"
        Twig_Autoloader::register();
        //Aca indicamos en que ruta se encuentra el html que va a recibir los parametros
        $loader = new Twig_Loader_Filesystem(CODE_ROOT . "/templates");
        $this->twig = new Twig_Environment($loader);

    }

    public static function render(...$args){

    }

    public function twig_render($path , $parameters){
        # es metedo intenta cubrir el render de twig, para
        # logear los errore si es necesaario, en algun momento
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
}