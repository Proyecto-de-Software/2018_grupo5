<?php
define('CODE_ROOT', dirname(__FILE__));
define('DEBUG', true);
include_once ("urls.php");
include_once('core/url_dispatcher/Dispatcher.php');


/*
$url_request = $_SERVER['REQUEST_URI']; //esta devolviendo algo como ex: grupo5/usuarios/alta

try {
	$dispatcher = new Dispatcher($urls, 'views/');
	echo $dispatcher->run($url_request);
} catch(Exception $e) {
	echo "Algo salio mal ---> " . $e;
}
*/


require_once (CODE_ROOT . "/../configs/bootstrap.php");
require_once (CODE_ROOT. "/models/entities/Genero.php");

$repo = $entityManager->getRepository('Genero');
$rows = $repo->findAll();
foreach ($rows as $row) {
    echo $row->getNombre() ."-";
}

