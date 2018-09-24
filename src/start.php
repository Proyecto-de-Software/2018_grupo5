<?php
define('CODE_ROOT', dirname(__FILE__));

# aca es desde cuando arranca a procesar la request
#define('PAGE_LOAD_TIME_START', time());
# aca tomo el tiempo desde que se creo la request
define('PAGE_LOAD_TIME_START', $_SERVER['REQUEST_TIME']);

require_once(CODE_ROOT . "/bootstrap.php");
require_once(CODE_ROOT . "/urls.php");

define('DEBUG', SETTINGS['debug']);

require_once('core/url_dispatcher/Dispatcher.php');


$url_request = $_SERVER['REQUEST_URI'];

session_start();

try {
    if(!DEBUG and isset($_SESSION['_DISPATCHER'])) {
        $dispatcher = unserialize($_SESSION['_DISPATCHER']);
    } else {
        /*
         * Con esto logramos una instancia compartida,
         *  para el mismo usuario,
         * en diferentes requetst.
         * Si tienen problemas solo hay que borrar la cookie */
        /**@TODO Hacer un benchmark entre esto y la opcion de unserializar. * */
        /** @var $dispatcher Dispatcher */
        $dispatcher = new Dispatcher();
        $dispatcher->setUrls(get_urls());
        $_SESSION['_DISPATCHER'] = serialize($dispatcher);
    }

    echo $dispatcher->run($url_request);
} catch (Exception $e) {
    echo "<h4>Error no catcheado en start.php</h4> ---> " . $e;
}
