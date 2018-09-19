<?php
/**
 * Rutas disponibles para la aplicacion
 */
include_once('core/url_dispatcher/Path.php');

$urls = [

    [Path::path_re("^/api/pacientes/^([a-zA-Z]+)/$", 'Pacientes_view::ver_pacientes')],

    [Path::path("/api/personas/<id:integer>/<dni:as>", 'aView::aMethod') ],

    [Path::path("/usuario/ver/(fecha)/(sueldo)",'aView::aMethod') ],

    //************************  Urls módulo usuario ********************************//
    $entidad = 'usuario'; 

    $funcion = 'create';
    [Path::path("/".$entidad."/".$funcion."/", $entidad."_controller"."::".$funcion) ],

    $funcion = 'read';
    [Path::path("/".$entidad."/".$funcion."/", $entidad."_controller"."::".$funcion) ],

    $funcion = 'update';
    [Path::path("/".$entidad."/".$funcion."/", $entidad."_controller"."::".$funcion) ],

    $funcion = 'delete';
    [Path::path("/".$entidad."/".$funcion."/", $entidad."_controller"."::".$funcion) ],


	//************************  Urls módulo paciente ********************************//
    $entidad = 'paciente'; 

    $funcion = 'create';
    [Path::path("/".$entidad."/".$funcion."/", $entidad."_controller"."::".$funcion) ],

    $funcion = 'read';
    [Path::path("/".$entidad."/".$funcion."/", $entidad."_controller"."::".$funcion) ],

    $funcion = 'update';
    [Path::path("/".$entidad."/".$funcion."/", $entidad."_controller"."::".$funcion) ],

    $funcion = 'delete';
    [Path::path("/".$entidad."/".$funcion."/", $entidad."_controller"."::".$funcion) ]

];