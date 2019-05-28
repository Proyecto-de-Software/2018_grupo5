<?php
require_once(CODE_ROOT . "/controllers/Controller.php");

use controllers\Controller;

class LoginController extends Controller {

    public static function render() {
        $instance = new LoginController();
        echo $instance->twig_render('login.html', []);
    }
}
