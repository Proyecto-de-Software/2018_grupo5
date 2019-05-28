<?php
define('CODE_ROOT', dirname(__FILE__));
require_once("core/SiMiL.php");
require_once("app/urls.php");

try {
    $context = new SiMiL();
    $context->addPaths($URLS_PATHS);
    $context->run();
} catch (Exception $e) {
    echo "<h4>Error catcheado en start.php!!! es un problema grave: </h4> ---> " . $e;
}