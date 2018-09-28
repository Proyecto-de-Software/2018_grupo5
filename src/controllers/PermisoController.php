<?php
require_once(CODE_ROOT . "/controllers/Controller.php");

use controllers\Controller;

class PermisoController extends Controller {

    public function index(...$args) {
        $permisos = $this->getModel('Permiso')->findAll();
        $contexto = [
            'permisos'=> $permisos,
        ];
        echo $this->twig_render('/modules/permisos/index.html', $contexto);
    }
}
