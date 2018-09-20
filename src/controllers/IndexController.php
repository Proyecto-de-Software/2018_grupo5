<?php
require_once(CODE_ROOT . "/controllers/Controller.php");
use controllers\Controller;

class IndexController extends Controller {

    public static function render(...$args){
        echo "Hola mundo! esto es el index";
    }

}