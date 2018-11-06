<?php

namespace controllers;

require_once(CODE_ROOT . "/vendor/twig/lib/Twig/Autoloader.php");
require_once(CODE_ROOT . "/vendor/autoload.php");
require_once(CODE_ROOT . "/core/session/Session.php");

require_once(CODE_ROOT . "/Dao/UsuarioDAO.php");

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Exception;
use Session;
use UsuarioDAO;
use Twig_Autoloader;
use Twig_Environment;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;
use Twig_Loader_Filesystem;


class Controller {
    public $twig;
    public $session;
    private $entityManager;


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

    private static function getEntityConfiruation() {
        // Create a simple "default" Doctrine ORM configuration for Annotations
        $isDevMode = true;
        return Setup::createAnnotationMetadataConfiguration(
            [CODE_ROOT . "/src/models"],
            $isDevMode,
            null,
            null,
            false);
    }

    public function userHasPermissionForCurrentMethod($level = 1) {
        /** Look the class and method who call this method
         * and create the permission name, then return
         *  if the current user has the permission.
         */
        $clazz = get_called_class();
        $method = debug_backtrace()[$level]['function'];
        $perm_name = $this->generatePermissionName($clazz, $method);
        return $this->userHasPermission($perm_name);
    }

    public function assertPermission() {
        /***
         * If the user don't have the permission,
         * assert with forbidden
         */
        if($this->userHasPermissionForCurrentMethod(2)) {
            return;
        } else {
            echo $this->twig_render('forbidden.html', []);
            die;
        }
    }

    public function userHasPermission($permissionName) {
        /**
         * Check if they don't need auth for use the website.
         * useful for testing purposes.
         */
        if(isset(SETTINGS['needAuthentication']) && !SETTINGS['needAuthentication']) {
            return true;
        }

        if($this->session->isAuthenticated()) {
            /** @var \Permiso $permission_instance */
            $permission_instance = $this->getModel('Permiso')->findOneBy(['nombre' => $permissionName]);

            if(!isset($permission_instance)) {
                return false;
            }
            return $this->user()->hasPermission($permission_instance);
        }
        return false;
    }

    /**
     * @return object|Usuario
     */
    public function user() {
        $userDao =  new UsuarioDAO();
        $id = $this->session->userId();
        return $userDao->getById($id);
    }

    /**
     * @return bool
     */
    public function userIsSuperUser() {
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
        if(!$this->entityManager->isOpen()) {
            $this->entityManager = EntityManager::create(SETTINGS['database'], self::getEntityConfiruation());
        }
        return $this->entityManager;
    }

    public function assertInMaintenance() {
        $state = $this->getConfigValue('sitio_activo');
        if(($state !== 'true') && $state !== null) {
            echo $this->twig_render("/maintenance.html", []);
            die();
        }
    }

    private function include_defaults(&$parameters) {
        $file = fopen(CODE_ROOT . '/version', 'r');
        $parameters['APP_VERSION'] = fgets($file);
        fclose($file);
        $parameters['DEBUG'] = DEBUG;
        $parameters['CSRF_TOKEN'] = CSRF_TOKEN;
        $parameters['PAGE_LOAD_TIME'] = number_format((float)(microtime_float() - START_REQUEST_MICROTIME), 2);
        $parameters['PAGE_RENDER_START_TIME'] = time();
        $parameters['session'] = $this->session;
        $parameters['settings'] = SETTINGS;
        $parameters['controller'] = $this;
        $parameters['user'] = $this->user();

        /*Get configs of db*/
        $parameters['config'] = [
            'titulo' => $this->getConfigValue('titulo'),
            'descripcion' => $this->getConfigValue('descripcion'),
            'email_de_contacto' => $this->getConfigValue('email_de_contacto'),
            'paginacion' => $this->getConfigValue('paginacion'),
            'sitio_activo' => $this->getConfigValue('sitio_activo'),
        ];
    }

    public function getConfigValue($variable) {
        $config = $this->getModel('Configuracion')->findOneBy(['variable' => $variable,]);
        $value = null;
        if(isset($config)) {
            $value = $config->getValor();
        }
        return $value;
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

    public function generatePermissionName($class_name, $method_name) {
        $ok = preg_match("/(.+)Controller/", $class_name, $matches);
        if($ok) {
            $class_name = $matches[1]; //remove Controller if exists at the end
        }

        return $this->camelCaseToSnake($class_name) . '_' . $this->camelCaseToSnake($method_name);
    }

    /**
     * @param $requiredArgs
     * @param bool $throwException
     * @return bool
     * @throws Exception
     */

    public function validateParams($requiredArgs, $throwException = false) {
        foreach ($requiredArgs as $arg) {
            if(!isset($_POST[$arg]) || ($_POST[$arg] == "")) {
                if($throwException) {
                    throw new \Exception("Faltan parametros");
                }
                return false;
            }
        }
        return true;
    }
}
