<?php
require_once(CODE_ROOT . "/controllers/Controller.php");

use controllers\Controller;

class PermisoController extends Controller {

    public function index(...$args) {

        echo $this->twig_render('/modules/permisos/index.html', []);

    }
}
