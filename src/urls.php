<?php
/**
 * Rutas disponibles para la aplicacion
 */
include_once(CODE_ROOT . '/core/url_dispatcher/Path.php');

function get_urls() {
    return [
        Path::path_re("/", 'IndexController->index'),
        Path::path_re("/contacto", 'IndexController->contacto'),
        Path::path_re("/login", 'LoginController::render'),

        Path::path_re("/auth/login", 'AuthenticationController->login'),
        Path::path_re("/auth/logout", 'AuthenticationController->logout'),

        Path::path_re("/api/localidades", 'LocalidadesController::apiLocalidades'),

        Path::path("/modulo/usuarios", 'UsuarioController->index'),
        Path::path("/modulo/usuarios/crear", 'UsuarioController->createView'),
        Path::path("/modulo/usuarios/modificar/<id:integer>", 'UsuarioController->updateView'),
        Path::path("/modulo/usuarios/configuracion", 'UsuarioController->configuracionView'),

        Path::path("/api/usuarios/eliminar/<id:integer>", 'UsuarioController->destroy'),
        Path::path("/api/usuarios/crear", 'UsuarioController->create'),
        Path::path("/api/usuarios/modificar", 'UsuarioController->update'),
        Path::path("/api/usuarios/cambiarContrasenia", 'UsuarioController->changeOwnPassword'),
        Path::path("/modulo/usuarios/<id:integer>/cambiarClave", 'UsuarioController->changePasswordView'),
        Path::path("/api/usuarios/<id:integer>/cambiarClave", 'UsuarioController->changePassword'),

        Path::path("/modulo/configuracion", 'ConfiguracionController->indexView'),
        Path::path("/modulo/configuracion/update", 'ConfiguracionController->update'),
        Path::path("/api/configuracion/mantenimiento", 'ConfiguracionController->setMantenimiento'),

        Path::path("/modulo/pacientes", 'PacienteController->index'),
        Path::path("/modulo/pacientes/crear", 'PacienteController->newView'),
        Path::path("/modulo/pacientes/crear-nn", 'PacienteController->newNNView'),
        Path::path("/modulo/pacientes/buscar", 'PacienteController->searchView'),
        Path::path("/modulo/pacientes/busqueda", 'PacienteController->search'),
        Path::path("/modulo/pacientes/modificar/<id:integer>", 'PacienteController->updateView'),
        Path::path("/modulo/pacientes/ver/<id:integer>", 'PacienteController->readView'),
        Path::path("/api/pacientes/crear", 'PacienteController->create'),
        Path::path("/api/pacientes/crear-nn", 'PacienteController->createNN'),

        Path::path("/modulo/reportes", 'ReportesController->index'),

        

        Path::path("/api/pacientes", 'PacienteController->pacientesJSON'),

        Path::path("/api/pacientes/eliminar/<id:integer>", 'PacienteController->delete'),
        Path::path("/api/pacientes/modificar/<id:integer>", 'PacienteController->update'),

        Path::path("/modulo/consultas/crear", 'ConsultaController->createView'),



        Path::path("/api/consultas/crear", 'ConsultaController->create'),
        Path::path("/api/consultas/<id:integer>", 'ConsultaController->index'),
        Path::path("/api/consultas/eliminar/<id:integer>", 'ConsultaController->destroy'),
        Path::path("/modulo/consultas/ver/<id:integer>", 'ConsultaController->view'),
        Path::path("/api/consultas/instituciones/paciente/<id:integer>", 'ConsultaController->getJsonForMap'),

        

        Path::path("/modulo/roles", 'RolController->indexView'),
        Path::path("/modulo/roles/show/<id:integer>/", 'RolController->show'),
        Path::path("/api/roles/modificar", 'RolController->update'),
        Path::path("/api/rol/getPermisos", 'RolController->getPermissionsForRole'),

        Path::path("/modulo/permisos", 'PermisoController->indexView'),


        Path::path("/api/localidades/partido/<id:integer>", 'LocalidadController->obtenerPorPartido'),
        Path::path("/api/region_sanitaria/partido/<id:integer>", 'RegionSanitariaController::obtenerPorPartido'),

        Path::path("/api/instituciones/", 'InstitucionController->getInstitucionesAsJSON'),
        Path::path("/api/instituciones/<id:integer>", 'InstitucionController->getInstitucionAsJSON'),
        Path::path("/api/instituciones/region-sanitaria/<id:integer>", 'InstitucionController->getInstitucionesByRegionAsJSON'),



        Path::path("/api/personas/<numero:integer>/<nombre:string>", ''),
        Path::path("/personas/<identificador:slug>/<nombre:string>", ''),



        Path::path_re("/db/loadData", 'SetupDbDataController->loadData'),
        Path::path_re("/db/generatePermissionData", 'SetupDbDataController->generatePermissionData'),
        Path::path_re("/db/createDefaultConfigs", 'SetupDbDataController->createDefaultConfigs'),
        Path::path_re("/db/showWarnings", 'SetupDbDataController->showWarnings'),

    ];
}
