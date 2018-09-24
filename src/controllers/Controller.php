<?php

namespace controllers;

require_once(CODE_ROOT . "/vendor/twig/lib/Twig/Autoloader.php");
require_once(CODE_ROOT . "/vendor/autoload.php");
require_once(CODE_ROOT . "/core/session/Session.php");

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Session;
use Twig_Autoloader;
use Twig_Environment;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;
use Twig_Loader_Filesystem;


abstract class Controller {
    public $twig;
    private $entityManager;
    public $session;

    public function __construct() {
        //Usando Twig, envio de parametros a archivo html dentro de folder "templates"
        Twig_Autoloader::register();
        //Aca indicamos en que ruta se encuentra el html que va a recibir los parametros
        $loader = new Twig_Loader_Filesystem(CODE_ROOT . "/templates");
        $this->twig = new Twig_Environment($loader);

        $this->entityManager = EntityManager::create(SETTINGS['database'], self::getEntityConfiruation());

        // Get or create the session for the current user
        $this->session = new Session();
    }
    private static function getEntityConfiruation(){
        // Create a simple "default" Doctrine ORM configuration for Annotations
        $isDevMode = true;
        return Setup::createAnnotationMetadataConfiguration([CODE_ROOT . "/src/models"], $isDevMode, null, null, false);
    }

    public static function render(...$args) {

    }


    public function userHasPermission($permission) {
        /**@todo considerar mover esto al modelo de usuario! una vez que este medianamente bien el modelo de datos. */
        if($this->session->isAuthenticated()) {
            if($this->userIsAdmin()) {
                return true;
            } else {
                $permission_instance = $this->getModel('Permiso')->findOneBy(['nombre' => $permission]);
                if(!isset($permission_instance)) {
                    echo "Permission doesn't exists!! {{" . $permission . "}}";
                    return false;
                }

                if($permission_instance->getUsuario()->get($this->user()->getId()) !== null) {
                    // el usuario tiene este permiso, especialmente asignado
                    echo "el usuario tiene este permiso, especialmente asignado.. ";
                    return true;
                }
                if(($permission_instance->getRol()->contains($this->user()->getRol())) !== null) {
                    echo "el usuario tiene este permiso heredado del rol.. ";
                    return false;
                }
            }
        }
        return false;

    }


    /**
     * @return null|Usuario
     */
    public function user() {
        $id = $this->session->userId();
        $user = $this->getModel('Usuario')->findOneBy(['id' => $id]);
        return $user;
    }

    /**
     * @return bool
     */
    public function userIsAdmin() {
        if($this->session->isAuthenticated()) {
            return $this->user()->getIsSuperuser();
        }
        return false;
    }

    public function getModel($repository) {
        require_once(CODE_ROOT . '/models/' . $repository . '.php');
        return $this->entityManager->getRepository($repository);
    }

    public function entityManager() {
        if (!$this->entityManager->isOpen()){
            $this->entityManager = EntityManager::create(SETTINGS['database'], self::getEntityConfiruation());
        }
        return $this->entityManager;
    }


    private function include_defaults(&$parameters) {
        $file = fopen(CODE_ROOT . '/version', 'r');
        $parameters['APP_VERSION'] = fgets($file);
        fclose($file);
        $parameters['DEBUG'] = DEBUG;
        $parameters['PAGE_LOAD_TIME'] = time() -  $_SERVER['REQUEST_TIME'];
        $parameters['PAGE_RENDER_START_TIME'] = time();
        $parameters['session'] = $this->session;
        $parameters['settings'] = SETTINGS;
    }

    public function twig_render($path, $parameters) {
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

    public function jsonResponse($data) {
        header('Content-Type: application/json');
        return json_encode($data);
    }

}