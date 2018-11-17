<?php

namespace controllers;

require_once(CODE_ROOT . "/core/vendor/twig/lib/Twig/Autoloader.php");
require_once(CODE_ROOT . "/core/session/Session.php");

use Exception;
use Session;
use UsuarioDAO;
use ConfiguracionDAO;

use Twig_Autoloader;
use Twig_Environment;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;
use Twig_Loader_Filesystem;


class Controller {
    public $twig;
    public $session;
    private $usuarioDao;
    private $configuracionDao;

    public function __construct() {
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem(CODE_ROOT . "/templates");
        $this->twig = new Twig_Environment($loader);
        /** @var UsuarioDAO $usuarioDao */
        $this->usuarioDao = new UsuarioDAO();
        $this->configuracionDao = new ConfiguracionDAO();
        // Get or create the session for the current user
        $this->session = new Session();
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

    function assertPermissionJson() {
        /**@doc: If the user don't have the permission,
         * assert with json response
         */
        if($this->userHasPermissionForCurrentMethod(2)) {
            return;
        } else {
            echo $this->jsonResponse([
                "error" => true,
                "msg" => "access forbidden"
            ]);
            die;
        }
    }

    public function userHasPermission($permissionName) {
        /** @doc: Check if they don't need authentication for use the website.
         *   useful for testing purposes.
         */

        if(isset(SETTINGS['needAuthentication']) && !SETTINGS['needAuthentication']) {
            return true;
        }

        if($this->session->isAuthenticated()) {
            return $this->usuarioDao->userHasPermission($this->session->userId(), $permissionName);
        }
        return false;
    }

    /**
     * @return object|Usuario
     */
    public function user() {
        $id = $this->session->userId();
        return $this->usuarioDao->getById($id);
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

    public function assertInMaintenance() {
        $state = $this->configuracionDao->getConfigValue('sitio_activo');
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
            'titulo' => $this->configuracionDao->getConfigValue('titulo'),
            'descripcion' => $this->configuracionDao->getConfigValue('descripcion'),
            'email_de_contacto' => $this->configuracionDao->getConfigValue('email_de_contacto'),
            'paginacion' => $this->configuracionDao->getConfigValue('paginacion'),
            'sitio_activo' => $this->configuracionDao->getConfigValue('sitio_activo'),
        ];
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

    function returnParamIfUserIsAdmin($param) {
        if ($this->userIsSuperUser()) {
            return $param;
        }
        return null;
    }

}
