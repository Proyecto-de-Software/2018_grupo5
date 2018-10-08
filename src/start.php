<?php
function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

define('CODE_ROOT', dirname(__FILE__));

require_once(CODE_ROOT . "/bootstrap.php");
require_once(CODE_ROOT . "/urls.php");

define('DEBUG', SETTINGS['debug']);
define('START_REQUEST_MICROTIME', microtime_float());

require_once('core/url_dispatcher/Dispatcher.php');
$url_request = $_SERVER['REQUEST_URI'];

session_start();
if ($handle = opendir('../../')) {

    while (false !== ($entry = readdir($handle))) {

        if ($entry != "." && $entry != "..") {

            echo "$entry\n";
        }
    }

    closedir($handle);
}
exit(0);
try {
    if(false and !DEBUG and isset($_SESSION['_DISPATCHER'])) {
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
