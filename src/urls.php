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
        Path::path("/modulo/usuarios/crear", 'UsuarioController::new'),
        Path::path("/modulo/pacientes", 'PacienteController::index'),

        Path::path("/api/personas/<numero:integer>/<nombre:string>", ''),
        Path::path("/personas/<identificador:slug>/<nombre:string>", ''),
    ];

    return $urls;
}

