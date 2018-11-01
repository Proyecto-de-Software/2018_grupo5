<?php

/**
 * Framework loader
 */

require_once("utils/time.php");
define('START_REQUEST_MICROTIME', microtime_float());

session_start();

require_once("url_dispatcher/Dispatcher.php");


/**
 * Protection mechanism for CSRF, and set globally the token
 */
require_once("middleware/CSRF/CSRF.php");
require_once("middleware/XSS/XSS.php");

$csrf = new ProtectorCSRF();
$csrf->aggressiveProtectRequestMethods();
define('CSRF_TOKEN', $csrf->getCSRFToken());


/**
 * User urls
 */

require_once(CODE_ROOT . '/urls.php');

function run(){
    $dispatcher = new Dispatcher();
    $dispatcher->setUrls(get_urls());
    return $dispatcher->run($_SERVER['REQUEST_URI']);
}


