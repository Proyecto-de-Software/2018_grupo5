<?php
require_once(CODE_ROOT . "/controllers/Controller.php");

use controllers\Controller;

class RolController extends Controller {

    public function indexView(...$args) {
        $this->assertPermission();
        $rol = $this->getModel('Rol')->findAll();
        $context = [
            'roles'=> $rol,
        ];
        echo $this->twig_render('/modules/roles/index.html', $context);
    }
}
