<?php
require_once(CODE_ROOT . "/controllers/Controller.php");

use controllers\Controller;

class RolController extends Controller {

    function indexView(...$args) {
        $this->assertPermission();
        $roles = $this->getModel('Rol')->findAll();
        $context = [
            'roles'=> $roles,
        ];
        echo $this->twig_render('/modules/roles/index.html', $context);
    }

    function show($args){
        $this->assertPermission();
        $rol = $this->getModel('Rol')->findOneBy(array('id'=>$args['id']));
        $permisos = $this->getModel('Permiso')->findAll();

        $context = [
            'rol'=> $rol,
            'permisos_disponibles' => $permisos
        ];
        echo $this->twig_render('/modules/roles/show.html', $context);
    }

    function update($args){
        echo "Por favor implementame";
    }

}
