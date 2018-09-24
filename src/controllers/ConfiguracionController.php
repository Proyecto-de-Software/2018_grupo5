<?php
require_once("Controller.php");
use controllers\Controller;

class ConfiguracionController  extends Controller {
    static function render(...$args) {
        $instance = new ConfiguracionController();
        $parameters = [];
        var_dump(glob(session_save_path() . '/*'));
        $parameters['active_session']= count(glob(session_save_path() . '/sess_*'));
        return $instance->twig_render("modules/configuracion/index.html", $parameters);

    }
}