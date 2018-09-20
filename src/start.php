<?php
define('CODE_ROOT', dirname(__FILE__));
include_once ("urls.php");
include_once('core/url_dispatcher/Dispatcher.php');

require_once (CODE_ROOT . "/core/controller/Controller.php");
use core\controller\Controller;


$url_request = $_SERVER['REQUEST_URI']; //esta devolviendo algo como ex: grupo5/usuarios/alta

try {

	$dispatcher = new Dispatcher($urls, 'views/');
	echo $dispatcher->run($url_request);

} catch(Exception $e) {
	echo "Algo salio mal ---> " . $e;
}
