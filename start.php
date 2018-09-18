<?
include_once ("urls.php");

$url_request = "/usuarios/alta";

$dispatcher = new Dispatcher($urls, 'views/');

echo $dispatcher->run($url_request);


