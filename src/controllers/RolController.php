<?php
require_once(CODE_ROOT . "/controllers/Controller.php");

use controllers\Controller;

class RolController extends Controller {

    public function index(...$args) {

        $rol = $this->getModel('Rol')->findAll();
        $contexto = [
            'roles'=> $rol,
        ];
        echo $this->twig_render('/modules/roles/index.html', $contexto);
    }
}
