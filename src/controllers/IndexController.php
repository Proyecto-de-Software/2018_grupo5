<?php
require_once(CODE_ROOT . "/controllers/Controller.php");
use controllers\Controller;

class IndexController extends Controller {

    public static function render(){
        echo "Hola mundo! esto es el index";
    }

}