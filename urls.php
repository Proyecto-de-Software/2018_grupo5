<?php
/**
 * Rutas disponibles para la aplicacion
 */

$urls = [
    [Path::re("^/api/pacientes/^([a-zA-Z]+)/$"), 'Pacientes_view::ver_pacientes'],
    [Path::path("/api/personas/<id:integer>/<dni:as>"), function(){}],
    [Path::path("/usuario/ver/(fecha)/(sueldo)"), ],
];