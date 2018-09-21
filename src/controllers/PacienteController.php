<?php

namespace controllers;

class PacienteController extends Controller {



	static function index(){
	    $instance = new PacienteController();
        return $instance->twig_render("modules/usuarios/index.html", []);
	}

	static function new(){

	}

	static function destroy(){

	}
	static function delete(){

	}
}
