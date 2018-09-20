<?php
/**
 * Rutas disponibles para la aplicacion
 */
include_once(CODE_ROOT . '/core/url_dispatcher/Path.php');
$urls = [

    Path::path_re("/^\/$/", 'IndexController::render'),

    Path::path_re("/^\/api\/pacientes\/([a-zA-Z]+)\//", 'PacientesController::ver_pacientes'),

    Path::path("/api/personas/<id:integer>/<dni:as>", 'UsuarioController::aMethod'),

    Path::path("/usuario/ver/(fecha)/(sueldo)", 'RolesController::aMethod'),

];
