<?php
require_once(CODE_ROOT . "/controllers/Controller.php");

use controllers\Controller;

class LoginController extends Controller {

    public static function render(...$args) {
        //echo "Hola mundo! esto es el index ";
        //var_dump($args);

        $instance = new LoginController();

        $parameters = [
            'titulo' => 'Login',
            'apellido' => 'Gonzalez',
            'id' => ($args[0]['identificador'] ?? 'None'),
        ];
        echo $instance->twig_render('login.html', $parameters);

    }
}
