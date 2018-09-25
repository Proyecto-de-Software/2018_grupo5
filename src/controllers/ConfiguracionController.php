<?php
require_once("Controller.php");
use controllers\Controller;

class ConfiguracionController  extends Controller {

     function index(...$args) {
        $this->assertPermission();
        $parameters = [];
        return $this->twig_render("modules/configuracion/index.html", $parameters);
    }

}