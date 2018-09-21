<?php
define('CODE_ROOT', dirname(__FILE__));
define('DEBUG', true);
include_once ("urls.php");
include_once('core/url_dispatcher/Dispatcher.php');
require_once(CODE_ROOT . "/bootstrap.php");
require_once (CODE_ROOT . "/models/Genero.php");

$url_request = $_SERVER['REQUEST_URI']; //esta devolviendo algo como ex: grupo5/usuarios/alta
$x=$entityManager->getRepository('Genero');
echo $x->findAll();
try {
	$dispatcher = new Dispatcher($urls);
	echo $dispatcher->run($url_request);
} catch(Exception $e) {
	echo "<h4>Error no catcheado en start.php</h4> ---> " . $e;
}

