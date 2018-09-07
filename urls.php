<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 04/09/18
 * Time: 20:41
 * Rutas disponibles para la aplicacion
 */
$urls = [
    [Path::re("^/api/pacientes/([a-zA-Z]+)/$"), function(){}],
    [Path::path("/api/personas/<id:integer>/<year:integer>"), function(){}],
];

new Dispatcher(urls);
Dispatcher->run($url);

foreach ($urls as $path=>$funciont) {
    if $path->isThis($url){
        echo $funciont($path->process());
    }
}