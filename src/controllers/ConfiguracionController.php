<?php
require_once("Controller.php");
use controllers\Controller;

class ConfiguracionController  extends Controller {
    static function render(...$args) {
        $instance = new ConfiguracionController();
        $parameters = [];
        $parameters['active_session'] = sizeof(glob(session_save_path() . '/sess_*'));
        return $instance->twig_render("modules/configuracion/index.html", $parameters);

    }
}