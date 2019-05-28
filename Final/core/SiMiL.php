<?php
/**
 * Framework loader
 */

require_once("utils/time.php");
define('FW_CODE_ROOT', dirname(__FILE__));
session_start();
require_once("dao/autoload.php");
require_once("model/autoload.php");
require_once("middleware/MiddlewareRunner.php");
require_once("url_dispatcher/Dispatcher.php");
require_once ("config/SettingLoader.php");

class SiMiL {

    private $middleware;
    private $dispatcher;
    private $settings;

    function __construct() {
        $this->middleware = new MiddlewareRunner();
        $this->dispatcher = new Dispatcher();

        // Load the configurations and set globally. Accessible with variable SETTINGS
        $this->settings = SettingLoader::getInstance();
        $this->settings->setSettingsGlobally();
    }

    function loadSecurityMiddleware() {
        $is_active_xss_protection = SETTINGS['MiddlewareRunner']['xss_protection']['enabled'] ?? true;
        if($is_active_xss_protection) {
            $this->middleware->register("XSS");
        }

        $is_active_csrf_protection = SETTINGS['MiddlewareRunner']['csrf_protection']['enabled'] ?? true;
        if($is_active_csrf_protection) {
            $this->middleware->register("CSRF");
        }
    }


    /**
     * Register the urls defined by the user.
     */
    function addPaths($array_paths) {
        $this->dispatcher->addPaths($array_paths);
    }


    function addPath($path) {
        $this->dispatcher->addPath($path);
    }

    /**
     * Process the request when the user call this function.
     * So the user can do other stuff before and after execute the
     * desired controller.
     */
    function run() {
        // Start the time "profiler"
        define('START_REQUEST_MICROTIME', microtime_float());

        $this->middleware->run();
        // find and run the contoller
        $this->dispatcher->run($_SERVER['REQUEST_URI']);
    }
}
