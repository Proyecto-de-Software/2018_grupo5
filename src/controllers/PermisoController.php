<?php
require_once(CODE_ROOT . "/controllers/Controller.php");

use controllers\Controller;

class PermisoController extends Controller {

    public function indexView(...$args) {
        $this->assertPermission();
        $permisoDao = new PermisoDAO();
        $context = [
            'permisos'=> $permisoDao->getAll(),
        ];
        echo $this->twig_render('/modules/permisos/index.html', $context);
    }
}
