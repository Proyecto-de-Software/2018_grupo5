<?php

/**
 * Framework loader
 */

require_once("utils/time.php");
define('START_REQUEST_MICROTIME', microtime_float());

session_start();

require_once("url_dispatcher/Dispatcher.php");


/**
 * Protection mechanism for CSRF,
 * is configurable via SETTINGS
 * and set globally the token
 */
$is_active_csrf_protection = SETTINGS['middleware']['csrf_protection']['enabled'] ?? true;

if ( $is_active_csrf_protection != false){
    require_once("middleware/CSRF/CSRF.php");
    $csrf = new ProtectorCSRF();
    $csrf->aggressiveProtectRequestMethods();
    define('CSRF_TOKEN', $csrf->getCSRFToken());
}

$is_active_xss_protection =  SETTINGS['middleware']['xss_protection']['enabled'] ?? true;
if ( $is_active_xss_protection != false) {
    require_once("middleware/XSS/XSS.php");
}


/**
 * User urls
 */

require_once(CODE_ROOT . '/urls.php');

function run() {
    $dispatcher = new Dispatcher();
    $dispatcher->setUrls(get_urls());
    return $dispatcher->run($_SERVER['REQUEST_URI']);
}


