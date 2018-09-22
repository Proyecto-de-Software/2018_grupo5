<?php
session_start();

define('CODE_ROOT', dirname(__FILE__));
define('DEBUG', true);
include_once('core/url_dispatcher/Dispatcher.php');
require_once(CODE_ROOT . "/bootstrap.php");
$url_request = $_SERVER['REQUEST_URI'];

try {
    /** @var $dispatcher Dispatcher */
	$dispatcher =  Dispatcher::getOrCreateInstance();
	if (!$dispatcher->hasUrls()){
	    echo "instanciando u rls";
        include_once ("urls.php");
        $dispatcher->setUrls($urls);
    }
	echo $dispatcher->run($url_request);
} catch(Exception $e) {
	echo "<h4>Error no catcheado en start.php</h4> ---> " . $e;
}

