<?php
namespace controllers;

require_once(CODE_ROOT . "/vendor/twig/lib/Twig/Autoloader.php");

use Twig_Autoloader;
use Twig_Environment;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;
use Twig_Loader_Filesystem;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;


abstract class Controller {
    public $twig;
    public $entityManager;

    public function __construct() {
        //Usando Twig, envio de parametros a archivo html dentro de folder "templates"
        Twig_Autoloader::register();
        //Aca indicamos en que ruta se encuentra el html que va a recibir los parametros
        $loader = new Twig_Loader_Filesystem(CODE_ROOT . "/templates");
        $this->twig = new Twig_Environment($loader);

        // Create a simple "default" Doctrine ORM configuration for Annotations
        $isDevMode = true;
        $config = Setup::createAnnotationMetadataConfiguration([CODE_ROOT . "/src/models"], $isDevMode, null, null, false);
        $this->entityManager = EntityManager::create(SETTINGS['database'], $config);
    }

    public static function render(...$args){

    }

    public function getModel($repository){
        require_once (CODE_ROOT . '/models/' . $repository . '.php');
        return $this->entityManager->getRepository( $repository);
    }


    private function include_defaults(&$parameters) {
        $file = fopen(CODE_ROOT . '/version','r');
        $parameters['APP_VERSION'] = fgets($file);
        fclose($file);
    }

    public function twig_render($path , $parameters){
        # es metedo intenta cubrir el render de twig, para
        # logear los errore si es necesaario, en algun momento
        $this->include_defaults($parameters);
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