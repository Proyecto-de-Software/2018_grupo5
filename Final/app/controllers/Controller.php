<?php

namespace controllers;

require_once(CODE_ROOT . "/core/session/Session.php");
require_once(CODE_ROOT . "/core/controller/SiMiLController.php");

use Exception;
use Session;
use UsuarioDAO;
use ConfiguracionDAO;
use SiMiLController;

const SITIO_ACTIVO = "sitio_activo";

class Controller extends SiMiLController {

    public $session;
    private $usuarioDao;
    private $configuracionDao;

    public function __construct() {
        parent::__construct();

        /** @var UsuarioDAO $usuarioDao */
        $this->usuarioDao = new UsuarioDAO();
        $this->configuracionDao = new ConfiguracionDAO();
        // Get or create the session for the current user
        $this->session = new Session();
    }

    public function userHasPermissionForCurrentMethod($level = 1) {
        /**
         * Look the class and method who call this method
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
                "msg" => "access forbidden",
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
        $state = $this->configuracionDao->getConfigValue(SITIO_ACTIVO);
        if(($state !== 'true') && $state !== null) {
            echo $this->twig_render("/maintenance.html", []);
            die();
        }
    }

    protected function add_defaults_twig_params(&$parameters) {
        $file = fopen(CODE_ROOT . '/app/version', 'r');
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
            SITIO_ACTIVO => $this->configuracionDao->getConfigValue(SITIO_ACTIVO),
        ];
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
            if(!isset($_POST[$arg]) || (trim($_POST[$arg]) == "")) {
                if($throwException) {
                    throw new \Exception("Faltan parametros");
                }
                return false;
            }
        }
        return true;
    }

    function returnParamIfUserIsAdmin($param) {
        if($this->userIsSuperUser()) {
            return $param;
        }
        return null;
    }

}
