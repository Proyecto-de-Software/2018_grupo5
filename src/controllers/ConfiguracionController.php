<?php
require_once("Controller.php");
use controllers\Controller;

class ConfiguracionController  extends Controller {
    static function render(...$args) {

        $instance = new ConfiguracionController();
        $instance->userHasPermissionForCurrentMethod();

        $parameters = [];
        return $instance->twig_render("modules/configuracion/index.html", $parameters);

    }
}