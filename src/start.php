<?php
session_start();

define('CODE_ROOT', dirname(__FILE__));
require_once('core/SiMiL.php');

define('START_REQUEST_MICROTIME', microtime_float());

require_once(CODE_ROOT . '/bootstrap.php');
require_once(CODE_ROOT . '/urls.php');
define('DEBUG', SETTINGS['debug']);


try {
    $dispatcher = new Dispatcher();
    $dispatcher->setUrls(get_urls());
    echo $dispatcher->run($_SERVER['REQUEST_URI']);
} catch (Exception $e) {
    echo "<h4>Error catcheado en start.php!!! es un problema grave: </h4> ---> " . $e;
}