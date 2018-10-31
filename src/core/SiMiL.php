<?php
/**
 * Framework loader
 */
require_once("utils/time.php");
require_once("url_dispatcher/Dispatcher.php");

require_once("middleware/XSS/XSS.php");
require_once("middleware/CSRF/CSRF.php");

$csrf = new ProtectorCSRF();
$csrf->aggressiveProtectRequestMethods();
