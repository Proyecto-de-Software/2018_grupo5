<?php
define('CODE_ROOT', dirname(__FILE__));

require_once(CODE_ROOT . '/core/utils/time.php');
define('START_REQUEST_MICROTIME', microtime_float());

require_once('core/url_dispatcher/Dispatcher.php');
require_once(CODE_ROOT . '/bootstrap.php');
require_once(CODE_ROOT . '/urls.php');
define('DEBUG', SETTINGS['debug']);

session_start();
try {
    $dispatcher = new Dispatcher();
    $dispatcher->setUrls(get_urls());
    echo $dispatcher->run($_SERVER['REQUEST_URI']);
} catch (Exception $e) {
    echo "<h4>Error catcheado en start.php!!! es un problema grave: </h4> ---> " . $e;
}