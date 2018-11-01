<?php
define('CODE_ROOT', dirname(__FILE__));
require_once(CODE_ROOT . '/bootstrap.php');
require_once('core/SiMiL.php');

try {
    echo run();
} catch (Exception $e) {
    echo "<h4>Error catcheado en start.php!!! es un problema grave: </h4> ---> " . $e;
}