<?php
require_once(CODE_ROOT . "/controllers/Controller.php");

use controllers\Controller;

class PermisoController extends Controller {

    public function indexView(...$args) {
        $this->assertPermission();
        $permissions = $this->getModel('Permiso')->findAll();
        $context = [
            'permisos'=> $permissions,
        ];
        echo $this->twig_render('/modules/permisos/index.html', $context);
    }
}
