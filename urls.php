<?php
/**
 * Rutas disponibles para la aplicacion
 */

require_once ('views/todos_los_pacientes.php');
use function views\ass as asd;

$urls = [
    [Path::re("^/api/pacientes/^([a-zA-Z]+)/$"), asd],
    [Path::path("/api/personas/<id:integer>/<dni:as>"), function(){}],
    [Path::path("/usuario/ver/(fecha)/(sueldo)"), ],
];