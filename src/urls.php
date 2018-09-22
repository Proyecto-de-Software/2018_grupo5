<?php
/**
 * Rutas disponibles para la aplicacion
 */
include_once(CODE_ROOT . '/core/url_dispatcher/Path.php');
$urls = [

    Path::path_re("/", 'IndexController::render'),
    Path::path_re("/index.html", 'IndexController::render'),
    Path::path_re("/login", 'LoginController::render'),

    Path::path_re("/auth/login", 'AuthenticationController::login'),
    Path::path_re("/auth/logout", 'AuthenticationController::logout'),

    Path::path("/modulo/usuarios", 'UsuarioController::index'),
    Path::path("/modulo/usuarios/crear", 'UsuarioController::new'),
    Path::path("/modulo/pacientes", 'PacienteController::index'),


    Path::path_re("/home/urlLinda/", 'IndexController::render'),
    Path::path_re("/usuario/([a-zA-Z]+)/([a-zA-Z]+)/", 'IndexController::render'),


    Path::path("/api/personas/<numero:integer>/<nombre:string>", 'IndexController::render'),
    Path::path("/personas/<identificador:slug>/<nombre:string>", 'IndexController::render'),

];
