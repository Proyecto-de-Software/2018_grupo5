<?php
require_once (CODE_ROOT . "/controllers/start.php");

class IndexController extends Controller {

    public static function render(){
        echo "Hola mundo";
    }

}