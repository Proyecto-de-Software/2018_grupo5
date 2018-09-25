<?php
/**
 * Rutas disponibles para la aplicacion
 */
include_once(CODE_ROOT . '/core/url_dispatcher/Path.php');

function get_urls() {
    $urls = [
        Path::path_re("/", 'IndexController::render'),
        Path::path_re("/login", 'LoginController::render'),

        Path::path_re("/auth/login", 'AuthenticationController::login'),
        Path::path_re("/auth/logout", 'AuthenticationController::logout'),

        Path::path_re("/api/localidades", 'LocalidadesController::apiLocalidades'),

        Path::path("/modulo/usuarios", 'UsuarioController::index'),
        Path::path("/modulo/usuarios/<id:integer>", 'UsuarioController::ver'),
        Path::path("/modulo/usuarios/crear", 'UsuarioController::new'),
        Path::path("/modulo/usuarios/modificar/<id:integer>", 'UsuarioController::update_view'),
        Path::path("/api/usuarios/eliminar/<id:integer>", 'UsuarioController::delete'),
        Path::path("/api/usuarios/crear", 'UsuarioController::create'),
        Path::path("/api/usuarios/modificar/<id:integer>", 'UsuarioController::update'),
        Path::path("/modulo/usuarios/<id:integer>/cambiarClave", 'UsuarioController::changePassword_view'),
        Path::path("/api/usuarios/<id:integer>/cambiarClave", 'UsuarioController::changePassword'),

        Path::path("/modulo/configuracion", 'ConfiguracionController::render'),


        Path::path("/modulo/pacientes", 'PacienteController::index'),
        Path::path("/modulo/pacientes/crear", 'PacienteController::new'),
        Path::path("/modulo/pacientes/buscar", 'PacienteController::searchView'),
        Path::path("/modulo/pacientes/busqueda", 'PacienteController::search'),
        Path::path("/modulo/pacientes/modificar/<id:integer>", 'PacienteController::updateView'),
        Path::path("/api/pacientes/crear", 'PacienteController::create'),
        Path::path("/api/pacientes/eliminar/<id:integer>", 'PacienteController::delete'),
        Path::path("/api/pacientes/modificar/<id:integer>", 'PacienteController::update'),


        Path::path("/api/localidades/partido/<id:integer>", 'LocalidadController::obtenerPorPartido'),
        Path::path("/api/region_sanitaria/partido/<id:integer>", 'RegionSanitariaController::obtenerPorPartido'),
        

        Path::path("/api/personas/<numero:integer>/<nombre:string>", ''),
        Path::path("/personas/<identificador:slug>/<nombre:string>", ''),

        Path::path_re("/db/loadData", 'SetupDbDataController::loadData'),
        Path::path_re("/db/generatePermissionData", 'SetupDbDataController::generatePermissionData'),

    ];

    return $urls;
}

