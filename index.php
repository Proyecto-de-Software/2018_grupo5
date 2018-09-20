<?php
include_once ("urls.php");
include_once('core/url_dispatcher/Dispatcher.php');

$url_request = $_SERVER['REQUEST_URI']; //esta devolviendo algo como ex: grupo5/usuarios/alta
echo $url_request;

$dispatcher = new Dispatcher($urls, 'views/');

echo $dispatcher->run($url_request);

echo $url_request;


?>