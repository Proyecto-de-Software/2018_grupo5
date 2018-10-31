<?php
/**
 * Framework loader
 */
require_once("utils/time.php");
require_once("url_dispatcher/Dispatcher.php");


/**
 * Protection mechanism for CSRF, and set globally the token
 */
require_once("middleware/CSRF/CSRF.php");
require_once("middleware/XSS/XSS.php");

$csrf = new ProtectorCSRF();
$csrf->aggressiveProtectRequestMethods();
define('CSRF_TOKEN', $csrf->getCSRFToken());

