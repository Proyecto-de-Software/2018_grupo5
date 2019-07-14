<?php
/**
 * Rutas disponibles para la aplicacion
 */
include_once(CODE_ROOT . '/core/url_dispatcher/Path.php');

$URLS_PATHS = [
    Path::path_re("/Final/", 'IndexController->index'),
    Path::path_re("/Final/contacto", 'IndexController->contacto'),
    Path::path_re("/Final/login", 'LoginController::render'),

    Path::path_re("/Final/auth/login", 'AuthenticationController->login'),
    Path::path_re("/Final/auth/logout", 'AuthenticationController->logout'),

    Path::path_re("/Final/api/localidades", 'LocalidadesController::apiLocalidades'),

    Path::path("/Final/modulo/usuarios", 'UsuarioController->index'),
    Path::path("/Final/modulo/usuarios/crear", 'UsuarioController->createView'),
    Path::path("/Final/modulo/usuarios/modificar/<id:integer>", 'UsuarioController->updateView'),
    Path::path("/Final/modulo/usuarios/configuracion", 'UsuarioController->configuracionView'),

    Path::path("/Final/api/usuarios/eliminar/<id:integer>", 'UsuarioController->destroy'),
    Path::path("/Final/api/usuarios/crear", 'UsuarioController->create'),
    Path::path("/Final/api/usuarios/modificar", 'UsuarioController->update'),
    Path::path("/Final/api/usuarios/cambiarContrasenia", 'UsuarioController->changeOwnPassword'),
    Path::path("/Final/modulo/usuarios/<id:integer>/cambiarClave", 'UsuarioController->changePasswordView'),
    Path::path("/Final/api/usuarios/<id:integer>/cambiarClave", 'UsuarioController->changePassword'),

    Path::path("/Final/modulo/configuracion", 'ConfiguracionController->indexView'),
    Path::path("/Final/modulo/configuracion/update", 'ConfiguracionController->update'),
    Path::path("/Final/api/configuracion/mantenimiento", 'ConfiguracionController->setMantenimiento'),

    Path::path("/Final/modulo/pacientes", 'PacienteController->index'),
    Path::path("/Final/modulo/pacientes/crear", 'PacienteController->newView'),
    Path::path("/Final/modulo/pacientes/crear-nn", 'PacienteController->newNNView'),
    Path::path("/Final/modulo/pacientes/buscar", 'PacienteController->searchView'),
    Path::path("/Final/modulo/pacientes/busqueda", 'PacienteController->search'),
    Path::path("/Final/modulo/pacientes/modificar/<id:integer>", 'PacienteController->updateView'),
    Path::path("/Final/modulo/pacientes/ver/<id:integer>", 'PacienteController->readView'),
    Path::path("/Final/api/pacientes/crear", 'PacienteController->create'),
    Path::path("/Final/api/pacientes/crear-nn", 'PacienteController->createNN'),

    Path::path("/Final/modulo/reportes", 'ReportesController->index'),
    Path::path("/Final/api/reportes/motivo", 'ReportesController->getJsonByReason'),
    Path::path("/Final/api/reportes/genero", 'ReportesController->getJsonByGender'),
    Path::path("/Final/api/reportes/localidad", 'ReportesController->getJsonByLocation'),


    Path::path("/Final/api/pacientes", 'PacienteController->pacientesJSON'),

    Path::path("/Final/api/pacientes/eliminar/<id:integer>", 'PacienteController->delete'),
    Path::path("/Final/api/pacientes/modificar/<id:integer>", 'PacienteController->update'),

    Path::path("/Final/modulo/consultas/crear", 'ConsultaController->createView'),


    Path::path("/Final/api/consultas/crear", 'ConsultaController->create'),
    Path::path("/Final/api/consultas/modificar", 'ConsultaController->update'),
    Path::path("/Final/modulo/consultas/<id:integer>", 'ConsultaController->index'),
    Path::path("/Final/modulo/consultas/eliminar/<id:integer>", 'ConsultaController->destroy'),
    Path::path("/Final/modulo/consultas/ver/<id:integer>", 'ConsultaController->view'),
    Path::path("/Final/modulo/consultas/modificar/<id:integer>", 'ConsultaController->updateView'),
    Path::path("/Final/api/consultas/instituciones/paciente/<id:integer>", 'ConsultaController->getJsonForMap'),


    Path::path("/Final/modulo/roles", 'RolController->indexView'),
    Path::path("/Final/modulo/roles/show/<id:integer>/", 'RolController->show'),
    Path::path("/Final/api/roles/modificar", 'RolController->update'),
    Path::path("/Final/api/rol/getPermisos", 'RolController->getPermissionsForRole'),

    Path::path("/Final/modulo/permisos", 'PermisoController->indexView'),


    Path::path("/Final/api/localidades/partido/<id:integer>", 'LocalidadController->obtenerPorPartido'),
    Path::path("/Final/api/region_sanitaria/partido/<id:integer>", 'RegionSanitariaController->obtenerPorPartido'),
    Path::path("/Final/api/partidos", 'PartidoController->verTodosLosPartidos'),

    # ------------------------------------
    #        API INSTITUCIONES
    # ------------------------------------
    Path::path("/Final/api/instituciones/crear", 'InstitucionController->create'),
    Path::path("/Final/api/instituciones/modificar/<id:integer>", 'InstitucionController->update'),
    Path::path("/Final/api/instituciones/<id:integer>", 'InstitucionController->getInstitucionAsJSON'),
    Path::path("/Final/api/instituciones/region-sanitaria/<id:integer>", 'InstitucionController->getInstitucionesByRegionAsJSON'),


    Path::path("/Final/api/personas/<numero:integer>/<nombre:string>", ''),
    Path::path("/Final/personas/<identificador:slug>/<nombre:string>", ''),


    Path::path_re("/Final/db/loadData", 'SetupDbDataController->loadData'),
    Path::path_re("/Final/db/generatePermissionData", 'SetupDbDataController->generatePermissionData'),
    Path::path_re("/Final/db/createDefaultConfigs", 'SetupDbDataController->createDefaultConfigs'),
    Path::path_re("/Final/db/showWarnings", 'SetupDbDataController->showWarnings'),

];
