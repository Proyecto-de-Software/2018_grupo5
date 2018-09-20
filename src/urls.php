<?php
/**
 * Rutas disponibles para la aplicacion
 */
include_once('core/url_dispatcher/Path.php');
$entidad='sdfa';
$funcion'sdfafsda';
$urls = [

    [Path::path_re("^/api/pacientes/^([a-zA-Z]+)/$", 'Pacientes_view::ver_pacientes')],

    [Path::path("/api/personas/<id:integer>/<dni:as>", 'aView::aMethod') ],

    [Path::path("/usuario/ver/(fecha)/(sueldo)",'aView::aMethod') ],

    //************************  Urls módulo usuario ********************************//

    [Path::path("/".$entidad."/".$funcion."/", $entidad."_controller"."::".$funcion) ],

    [Path::path("/".$entidad."/".$funcion."/", $entidad."_controller"."::".$funcion) ],

    [Path::path("/".$entidad."/".$funcion."/", $entidad."_controller"."::".$funcion) ],

    [Path::path("/".$entidad."/".$funcion."/", $entidad."_controller"."::".$funcion) ],


	//************************  Urls módulo paciente ********************************//

    [Path::path("/".$entidad."/".$funcion."/", $entidad."_controller"."::".$funcion) ],

    [Path::path("/".$entidad."/".$funcion."/", $entidad."_controller"."::".$funcion) ],

    [Path::path("/".$entidad."/".$funcion."/", $entidad."_controller"."::".$funcion) ],

    [Path::path("/".$entidad."/".$funcion."/", $entidad."_controller"."::".$funcion) ]

];
