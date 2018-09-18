<?
include_once ("urls.php");

$url_request = "/usuarios/alta";

$dispatcher = new Dispatcher($urls);

echo $dispatcher->run($url_request);


